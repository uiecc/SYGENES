<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Entity\Student;
use App\Entity\Level;
use App\Entity\FieldManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Psr\Log\LoggerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class ExcelImportService
{
    private $entityManager;
    private $passwordHasher;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->logger = $logger;
    }

    private function normalizeSex(?string $sex): string
    {
        return match (strtoupper(trim($sex ?? ''))) {
            'M', 'MASCULIN', 'HOMME' => 'M',
            'F', 'FÉMININ', 'FEMININ', 'FEMME' => 'F',
            default => ''
        };
    }

    public function importStudents(string $filePath, FieldManager $fieldManager): array
    {
        // Augmenter la limite de mémoire
        ini_set('memory_limit', '512M');

        // Créer un lecteur de flux
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $batchSize = 20; // Nombre de lignes à traiter par lot
        $i = 0;
        $failedStudents = []; // Pour stocker les étudiants non importés
        $field = $fieldManager->getField();
        $previousLevel = null; // Pour stocker le niveau précédent

        foreach ($sheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);
            $studentData = [];
            foreach ($cellIterator as $cell) {
                $studentData[] = $cell->getValue();
            }

            try {
                // Vérifier si la ligne est valide (par exemple, ignorer les lignes vides ou les en-têtes)
                if ($i === 0) {
                    // Supposons que la première ligne est l'en-tête
                    $i++;
                    continue;
                }

                // Créer une nouvelle instance de Student et définir ses propriétés
                $student = new Student();
                $student->setMatricule($studentData[0] ?? '');
                $student->setLastName($studentData[1] ?? '');
                $student->setFirstName($studentData[11] ?? 'Prénom par défaut');
                $student->setCni($studentData[2] ?? '');
                $student->setProfilePhoto('default.jpg');

                // Vérifier et formater la date de naissance
                if (is_numeric($studentData[3])) {
                    // Convertir la valeur numérique en date
                    $dateOfBirth = \DateTime::createFromFormat('Y-m-d', gmdate('Y-m-d', ($studentData[3] - 25569) * 86400));
                } elseif (!empty($studentData[3]) && \DateTime::createFromFormat('d/m/Y', $studentData[3])) {
                    $dateOfBirth = \DateTime::createFromFormat('d/m/Y', $studentData[3]);
                } elseif (!empty($studentData[3]) && \DateTime::createFromFormat('d/m/y', $studentData[3])) {
                    $dateOfBirth = \DateTime::createFromFormat('d/m/y', $studentData[3]);
                } else {
                    // En cas d'erreur de format de la date, utiliser la date d'aujourd'hui
                    $dateOfBirth = new \DateTime();
                    $this->logger->error('Format de date de naissance invalide, utilisation de la date d\'aujourd\'hui: ' . $studentData[3]);
                }
                $student->setDateOfBirth($dateOfBirth);

                $student->setPlaceOfBirth($studentData[4] ?? '');
                $student->setSex($this->normalizeSex($studentData[5] ?? ''));
                $student->setPhoneNumber($studentData[6] ?? '');
                $student->setEmail($studentData[7] ?? '');
                $student->setNationality($studentData[8] ?? '');

                // Récupérer le niveau
                $levelCode = $studentData[10] ?? '';

                if ($levelCode) {
                    $this->logger->info('Recherche du niveau avec le code: ' . $levelCode . ' pour la filière: ' . $field->getName());
                    $level = $this->entityManager->getRepository(Level::class)->findOneBy([
                        'code' => $levelCode,
                        'field' => $field,
                    ]);
                } else {
                    $level = $previousLevel;
                }

                if (!$level) {
                    $this->logger->error('Niveau introuvable avec le code: ' . $levelCode . ' pour l\'étudiant: ' . $student->getUsername());
                    $failedStudents[] = [
                        'student' => $student,
                        'reason' => 'Niveau introuvable',
                    ];
                    continue;
                }

                $student->setLevel($level);
                $previousLevel = $level; // Stocker le niveau pour le prochain étudiant si nécessaire

                // Créer un compte utilisateur pour l'étudiant
                if ($studentData[2]) {
                    $username = $studentData[2];
                } elseif ($studentData[6]) {
                    $username = $studentData[6];
                } else {
                    $username = $studentData[7];
                }

                if (!$username) {
                    $this->logger->error('Le nom d\'utilisateur est invalide ou manquant pour l\'étudiant : ' . $student->getLastName());
                    $failedStudents[] = [
                        'student' => $student,
                        'reason' => 'Nom d\'utilisateur invalide ou manquant',
                    ];
                    continue;
                }
                $password = $this->passwordHasher->hashPassword($student, $username); // Hasher le mot de passe

                $student->setUsername($username);
                $student->setPassword($password);

                // Ajouter l'étudiant à l'entité
                $this->logger->info('Persisting student: ' . $student->getUsername());
                $this->entityManager->persist($student);

                // Traiter les données par lots pour éviter l'épuisement de la mémoire
                if ($this->entityManager->isOpen()) {
                    $this->logger->info('Flushing batch of students');
                    $this->entityManager->flush();
                    $this->entityManager->clear();
                } else {
                    $this->logger->error('L\'EntityManager est fermé.');
                }
            } catch (UniqueConstraintViolationException $e) {
                $this->logger->error('Erreur de contrainte d\'unicité pour l\'étudiant : ' . $student->getUsername() . ' - ' . $e->getMessage());
                $failedStudents[] = [
                    'student' => $student,
                    'reason' => 'Contrainte d\'unicité violée (email ou username déjà existant)',
                ];
                // Retirer l'étudiant problématique de l'EntityManager
                $this->entityManager->detach($student);
                // Continuer avec les autres étudiants
                continue;
            } catch (\Exception $e) {
                $this->logger->error('Erreur lors de l\'importation de l\'étudiant : ' . ($studentData[1] ?? 'Inconnu') . ' - ' . $e->getMessage());
                $failedStudents[] = [
                    'student' => $student,
                    'reason' => $e->getMessage(),
                ];
                // Retirer l'étudiant problématique de l'EntityManager
                $this->entityManager->detach($student);
                // Continuer avec les autres étudiants
                continue;
            }

            $i++;
        }

        // Sauvegarder les dernières entités persistées
        try {
            if ($this->entityManager->isOpen()) {
                $this->logger->info('Flushing remaining students');
                $this->entityManager->flush();
                $this->entityManager->clear();
            } else {
                $this->logger->error('L\'EntityManager est fermé.');
            }
        } catch (UniqueConstraintViolationException $e) {
            $this->logger->error('Erreur lors du flush final : ' . $e->getMessage());
        } catch (\Exception $e) {
            $this->logger->error('Erreur lors du flush final : ' . $e->getMessage());
        }

        return $failedStudents;
    }
}
