<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use App\Entity\Student;
use App\Entity\Level;
use App\Entity\FieldManager;
use App\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Persistence\ManagerRegistry;

class ExcelImportService
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;
    private LoggerInterface $logger;
    private ValidatorInterface $validator;
    private ManagerRegistry $doctrine;

    public function __construct(
        EntityManagerInterface $entityManager, 
        UserPasswordHasherInterface $passwordHasher, 
        LoggerInterface $logger,
        ValidatorInterface $validator,
        ManagerRegistry $doctrine
    ) {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->logger = $logger;
        $this->validator = $validator;
        $this->doctrine = $doctrine;
    }

    /**
     * Normalise le sexe en M/F
     */
    private function normalizeSex(?string $sex): string
    {
        if (empty($sex)) {
            return '';
        }
        
        return match (strtoupper(trim($sex))) {
            'M', 'MASCULIN', 'HOMME', 'H', 'MASCULIN(E)', 'GARÇON', 'GARCON' => 'M',
            'F', 'FÉMININ', 'FEMININ', 'FEMME', 'FEMININ(E)', 'FILLE' => 'F',
            default => ''
        };
    }

    /**
     * Détecte et formate correctement la date de naissance
     */
    private function parseDate($rawDate): ?\DateTime
    {
        if (empty($rawDate)) {
            return null;
        }
        
        // Si la date est un nombre (format Excel)
        if (is_numeric($rawDate)) {
            try {
                // Convertir le nombre flottant en entier pour gmdate
                $timestamp = (int)(($rawDate - 25569) * 86400);
                $dateStr = gmdate('Y-m-d', $timestamp);
                $date = \DateTime::createFromFormat('Y-m-d', $dateStr);
                return $date ?: null; // Retourne null si la création a échoué
            } catch (\Exception $e) {
                $this->logger->warning('Erreur lors de la conversion de la date Excel: ' . $e->getMessage());
                return null;
            }
        }
        
        // Convertir en string si ce n'est pas déjà le cas
        if (!is_string($rawDate)) {
            $rawDate = (string)$rawDate;
        }
        
        // Essayer différents formats de date
        $formats = [
            'd/m/Y', // 23/02/1997
            'd-m-Y', // 23-02-1997
            'Y-m-d', // 1997-02-23
            'd/m/y', // 23/02/97
            'j/n/Y', // 23/2/1997
            'j-n-Y', // 23-2-1997
            'Y/m/d', // 1997/02/23
        ];
        
        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, $rawDate);
            if ($date !== false && $date->format($format) == $rawDate) {
                $this->logger->info('Date reconnue au format: ' . $format);
                return $date;
            }
        }
        
        // Format non reconnu
        $this->logger->warning('Format de date non reconnu: ' . $rawDate);
        return null;
    }
    
    /**
     * Extrait le nom et le prénom à partir d'une chaîne combinée
     */
    private function extractNameParts(string $fullName): array
    {
        $parts = explode(' ', trim($fullName));
        
        if (count($parts) == 1) {
            return [
                'lastName' => $parts[0],
                'firstName' => ''
            ];
        }
        
        // Première partie est le nom de famille
        $lastName = $parts[0];
        
        // Le reste est le prénom
        $firstName = implode(' ', array_slice($parts, 1));
        
        return [
            'lastName' => $lastName,
            'firstName' => $firstName
        ];
    }

    /**
     * Vérifie si l'EntityManager est encore ouvert, sinon le réinitialise
     */
    private function ensureEntityManagerIsOpen(): void
    {
        if (!$this->entityManager->isOpen()) {
            $this->logger->warning('EntityManager fermé, réinitialisation...');
            $this->entityManager = $this->doctrine->resetManager();
        }
    }

    /**
     * Importe les étudiants à partir d'un fichier Excel
     * 
     * @param string $filePath Le chemin vers le fichier Excel
     * @param FieldManager $fieldManager Le gestionnaire de filière
     * 
     * @return array Les résultats de l'import avec les statistiques et les erreurs
     */
    public function importStudents(string $filePath, FieldManager $fieldManager): array
    {
        // Augmenter la limite de mémoire et de temps d'exécution
        ini_set('memory_limit', '512M');
        set_time_limit(300); // 5 minutes
        
        $this->logger->info('Début de l\'importation depuis: ' . $filePath);

        try {
            // Créer un lecteur de flux
            $reader = IOFactory::createReaderForFile($filePath);
            $reader->setReadDataOnly(true);
            
            $spreadsheet = $reader->load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            
            $this->logger->info('Fichier Excel chargé, feuille active: ' . $sheet->getTitle());
            
            // Conversion de la feuille en tableau pour un traitement plus facile
            $dataArray = $sheet->toArray(null, true, true, false);
            
            // Filtrer les lignes vides
            $dataArray = array_filter($dataArray, function($row) {
                return count(array_filter($row, function($cell) {
                    return !empty($cell);
                })) > 0;
            });
            
            // Réindexer le tableau
            $dataArray = array_values($dataArray);
            
            $this->logger->info('Nombre de lignes non vides: ' . count($dataArray));
            
            if (count($dataArray) <= 1) {
                throw new \Exception('Le fichier ne contient pas assez de données (seulement l\'en-tête ou vide).');
            }
            
            $batchSize = 10; // Réduit pour éviter les problèmes de mémoire
            $count = 0;
            $successCount = 0;
            $failedStudents = []; // Pour stocker les étudiants non importés
            
            // S'assurer que le fieldManager est rechargé depuis la base de données
            $this->ensureEntityManagerIsOpen();
            $fieldManager = $this->entityManager->find(FieldManager::class, $fieldManager->getId());
            $field = $fieldManager->getField();
            
            // Structure attendue:
            // 0: matricule | 1: nom et prenoms | 2: cni | 3: date de naissance | 4: lieu de naissance 
            // 5: sexe | 6: telephone | 7: email | 8: pays | 9: **filiere** | 10: niveau
            
            // Ignorer la première ligne (en-tête)
            for ($rowIndex = 1; $rowIndex < count($dataArray); $rowIndex++) {
                $rowData = $dataArray[$rowIndex];
                
                $this->logger->info("Traitement de la ligne " . ($rowIndex + 1) . ": " . implode(' | ', $rowData));
                
                try {
                    // S'assurer que l'EntityManager est ouvert
                    $this->ensureEntityManagerIsOpen();
                    
                    // S'assurer qu'il y a assez de colonnes
                    if (count($rowData) < 8) {
                        throw new \Exception('Nombre de colonnes insuffisant');
                    }
                    
                    // Créer une nouvelle instance de Student
                    $student = new Student();
                    
                    // Matricule (colonne 0)
                    $student->setMatricule(trim($rowData[0] ?? ''));
                    
                    // Nom et prénoms (colonne 1)
                    $fullName = trim($rowData[1] ?? '');
                    if (empty($fullName)) {
                        throw new \Exception('Nom et prénoms manquants');
                    }
                    
                    $nameParts = $this->extractNameParts($fullName);
                    $student->setLastName($nameParts['lastName']);
                    $student->setFirstName($nameParts['firstName']);
                    
                    // CNI (colonne 2)
                    $student->setCni(trim($rowData[2] ?? ''));
                    
                    // Photo de profil par défaut
                    $student->setProfilePhoto('default.jpg');
                    
                    // Date de naissance (colonne 3)
                    $dateOfBirth = $this->parseDate($rowData[3] ?? null);
                    if ($dateOfBirth !== null) {
                        $student->setDateOfBirth($dateOfBirth);
                    } else {
                        // Date par défaut
                        $student->setDateOfBirth(new \DateTime('2000-01-01'));
                        $this->logger->warning('Date de naissance invalide pour ' . $fullName);
                    }
                    
                    // Lieu de naissance (colonne 4)
                    $student->setPlaceOfBirth(trim($rowData[4] ?? ''));
                    
                    // Sexe (colonne 5)
                    $sex = $this->normalizeSex($rowData[5] ?? '');
                    $student->setSex($sex);
                    if (empty($sex)) {
                        $this->logger->warning('Sexe non reconnu pour ' . $fullName);
                    }
                    
                    // Téléphone (colonne 6)
                    $phone = preg_replace('/[^0-9+]/', '', $rowData[6] ?? '');
                    $student->setPhoneNumber($phone);
                    
                    // Email (colonne 7)
                    $email = strtolower(trim($rowData[7] ?? ''));
                    $student->setEmail($email);
                    
                    // Nationalité/pays (colonne 8)
                    $student->setNationality(trim($rowData[8] ?? ''));
                    
                    // Niveau (colonne 10)
                    $levelCode = trim($rowData[10] ?? '');
                    
                    $this->logger->info('Code de niveau détecté: ' . $levelCode);
                    
                    if (empty($levelCode)) {
                        throw new \Exception('Niveau manquant');
                    }
                    
                    // Rechercher le niveau avec l'EntityManager actuel
                    $level = $this->entityManager->getRepository(Level::class)->findOneBy([
                        'code' => $levelCode,
                        'field' => $field,
                    ]);
                    
                    if (!$level) {
                        // Recherche insensible à la casse
                        $qb = $this->entityManager->createQueryBuilder();
                        $qb->select('l')
                           ->from(Level::class, 'l')
                           ->where('LOWER(l.code) = LOWER(:code)')
                           ->andWhere('l.field = :field')
                           ->setParameter('code', $levelCode)
                           ->setParameter('field', $field);
                        
                        $levels = $qb->getQuery()->getResult();
                        if (count($levels) > 0) {
                            $level = $levels[0];
                        } else {
                            throw new \Exception('Niveau introuvable: ' . $levelCode);
                        }
                    }
                    
                    $student->setLevel($level);
                    
                    // Créer un nom d'utilisateur unique
                    // Priorité: CNI > Téléphone > Email > Matricule
                    $username = '';
                    if (!empty($student->getCni())) {
                        $username = $student->getCni();
                    } elseif (!empty($student->getPhoneNumber())) {
                        $username = $student->getPhoneNumber();
                    } elseif (!empty($student->getEmail())) {
                        $username = $student->getEmail();
                    } elseif (!empty($student->getMatricule())) {
                        $username = $student->getMatricule();
                    }
                    
                    if (empty($username)) {
                        throw new \Exception('Impossible de générer un nom d\'utilisateur');
                    }
                    
                    // Vérifier si l'utilisateur existe déjà
                    $existingUser = $this->entityManager->getRepository(Student::class)
                        ->findOneBy(['username' => $username]);
                    
                    if ($existingUser) {
                        throw new \Exception('Utilisateur déjà existant avec ce nom d\'utilisateur: ' . $username);
                    }

                    $roleStudent = $this->entityManager->getRepository(Role::class)->findOneBy(['name' => 'ROLE_STUDENT']);
    
                    if (!$roleStudent) {
                        // Créer le rôle s'il n'existe pas
                        $roleStudent = new Role();
                        $roleStudent->setName('ROLE_STUDENT');
                        $roleStudent->setDescription('Rôle pour les étudiants');
                        $this->entityManager->persist($roleStudent);
                    }
                    
                    $student->addUserRole($roleStudent);
                
                    
                    $password = $this->passwordHasher->hashPassword($student, $username);
                    $student->setUsername($username);
                    $student->setPassword($password);
                    
                    // Valider l'entité
                    $violations = $this->validator->validate($student);
                    if (count($violations) > 0) {
                        $errorMessages = [];
                        foreach ($violations as $violation) {
                            $errorMessages[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
                        }
                        throw new \Exception('Validation échouée: ' . implode(', ', $errorMessages));
                    }
                    
                    // Persister l'étudiant
                    $this->entityManager->persist($student);
                    $successCount++;
                    
                    // Flush par lot plus fréquent
                    if (($count % $batchSize) === 0 && $count > 0) {
                        $this->logger->info('Flush du lot courant (ligne ' . ($rowIndex + 1) . ')');
                        $this->entityManager->flush();
                        
                        // Réinitialiser l'EntityManager pour libérer de la mémoire
                        $this->entityManager->clear(Student::class);
                        // Ne pas effacer Level et Field pour éviter des requêtes supplémentaires
                    }
                    
                    $count++;
                    
                } catch (UniqueConstraintViolationException $e) {
                    $this->logger->error('Erreur de contrainte d\'unicité à la ligne ' . ($rowIndex + 1) . ': ' . $e->getMessage());
                    $failedStudents[] = [
                        'student' => $student ?? new Student(),
                        'reason' => 'Contrainte d\'unicité violée',
                    ];
                    
                    // Détacher l'étudiant problématique
                    if (isset($student) && $this->entityManager->contains($student)) {
                        $this->entityManager->detach($student);
                    }
                    
                } catch (\Exception $e) {
                    $this->logger->error('Erreur à la ligne ' . ($rowIndex + 1) . ': ' . $e->getMessage());
                    $failedStudents[] = [
                        'student' => $student ?? new Student(),
                        'reason' => 'Erreur: ' . $e->getMessage(),
                    ];
                    
                    // Détacher l'étudiant problématique
                    if (isset($student) && $this->entityManager->contains($student)) {
                        $this->entityManager->detach($student);
                    }
                    
                    // Vérifier si l'EntityManager est toujours ouvert
                    $this->ensureEntityManagerIsOpen();
                }
            }
            
            // Sauvegarder les dernières entités
            if ($successCount > 0) {
                $this->ensureEntityManagerIsOpen();
                $this->logger->info('Flush final des entités restantes');
                $this->entityManager->flush();
            }
            
            $this->logger->info("Import terminé: $successCount étudiants importés, " . count($failedStudents) . " échecs");
            
            return [
                'success' => $successCount,
                'failed' => $failedStudents,
                'total' => $count
            ];
            
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de l\'importation: ' . $e->getMessage());
            throw $e;
        }
    }
}