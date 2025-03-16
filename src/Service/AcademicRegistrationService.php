<?php

namespace App\Service;

use App\Entity\Student;
use App\Entity\UE;
use App\Entity\StudentUE;
use App\Entity\Level;
use App\Repository\UERepository;
use App\Repository\StudentUERepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class AcademicRegistrationService
{
    private EntityManagerInterface $entityManager;
    private UERepository $ueRepository;
    private StudentUERepository $studentUERepository;
    private Security $security;

    public function __construct(
        EntityManagerInterface $entityManager,
        UERepository $ueRepository,
        StudentUERepository $studentUERepository,
        Security $security
    ) {
        $this->entityManager = $entityManager;
        $this->ueRepository = $ueRepository;
        $this->studentUERepository = $studentUERepository;
        $this->security = $security;
    }

    /**
     * Obtient toutes les UE disponibles pour un niveau
     */
    public function getAvailableUEsForLevel(Level $level): array
    {
        return $this->ueRepository->findByLevelOrderedBySemester($level);
    }

    /**
     * Retourne les UE déjà enregistrées par un étudiant pour une année académique
     */
    public function getRegisteredUEs(Student $student, string $academicYear): array
    {
        return $this->studentUERepository->findUEsByStudentAndAcademicYear($student, $academicYear);
    }

    /**
     * Enregistre un étudiant à une UE
     */
    public function registerStudentToUE(Student $student, UE $ue, string $academicYear): void
    {
        // Vérifier si l'étudiant est déjà inscrit à cette UE
        if ($this->studentUERepository->isStudentRegisteredToUE($student, $ue, $academicYear)) {
            return;
        }

        $studentUE = new StudentUE();
        $studentUE->setStudent($student);
        $studentUE->setUe($ue);
        $studentUE->setAcademicYear($academicYear);
        $studentUE->setRegisteredAt(new \DateTimeImmutable());
        $studentUE->setStatus('registered');
        
        // Ajouter la relation bidirectionnelle
        $student->addStudentUE($studentUE);
        
        $this->entityManager->persist($studentUE);
        // Ne pas faire de flush ici, on le fera en masse à la fin
    }

    /**
     * Désinscrit un étudiant d'une UE
     */
    public function unregisterStudentFromUE(Student $student, UE $ue, string $academicYear): void
    {
        $studentUE = $this->studentUERepository->findOneBy([
            'student' => $student,
            'ue' => $ue,
            'academicYear' => $academicYear
        ]);
        
        if ($studentUE) {
            $this->entityManager->remove($studentUE);
            // Ne pas faire de flush ici, on le fera en masse à la fin
        }
    }

    /**
     * Inscrit automatiquement un étudiant à toutes les UE obligatoires de son niveau
     */
    public function registerStudentToCompulsoryUEs(Student $student, string $academicYear): array
    {
        $level = $student->getLevel();
        $compulsoryUEs = $this->ueRepository->findCompulsoryByLevel($level);
        $registeredUEs = [];
        
        foreach ($compulsoryUEs as $ue) {
            if (!$this->studentUERepository->isStudentRegisteredToUE($student, $ue, $academicYear)) {
                $this->registerStudentToUE($student, $ue, $academicYear);
                $registeredUEs[] = $ue;
            } else {
                $registeredUEs[] = $ue;
            }
        }
        
        // Flush une seule fois à la fin pour des raisons de performance
        $this->entityManager->flush();
        
        return $registeredUEs;
    }
    
    /**
     * Met à jour toutes les inscriptions d'un étudiant pour une année académique
     */
    public function updateStudentRegistrations(Student $student, array $selectedUEs, string $academicYear): void
    {
        // Récupérer toutes les UE obligatoires
        $level = $student->getLevel();
        $compulsoryUEs = $this->ueRepository->findCompulsoryByLevel($level);
        $compulsoryUEIds = array_map(fn($ue) => $ue->getId(), $compulsoryUEs);
        
        // Récupérer les inscriptions actuelles
        $currentRegistrations = $this->studentUERepository->findByStudentAndAcademicYear($student, $academicYear);
        
        // Supprimer les inscriptions aux UE non obligatoires qui ne sont pas dans la sélection
        foreach ($currentRegistrations as $studentUE) {
            $ueId = $studentUE->getUe()->getId();
            $isCompulsory = in_array($ueId, $compulsoryUEIds);
            
            if (!$isCompulsory) {
                $isSelected = false;
                foreach ($selectedUEs as $selectedUE) {
                    if ($selectedUE->getId() === $ueId) {
                        $isSelected = true;
                        break;
                    }
                }
                
                if (!$isSelected) {
                    $this->entityManager->remove($studentUE);
                }
            }
        }
        
        // Ajouter les nouvelles inscriptions
        foreach ($selectedUEs as $ue) {
            $isRegistered = false;
            foreach ($currentRegistrations as $studentUE) {
                if ($studentUE->getUe()->getId() === $ue->getId()) {
                    $isRegistered = true;
                    break;
                }
            }
            
            if (!$isRegistered) {
                $this->registerStudentToUE($student, $ue, $academicYear);
            }
        }
        
        // Flush une seule fois à la fin
        $this->entityManager->flush();
    }
    
    /**
     * Vérifie si les UE sélectionnées respectent les règles académiques
     * Par exemple, nombre de crédits minimum, prérequis, etc.
     */
    public function validateUESelection(Student $student, array $selectedUEs, string $academicYear): array
    {
        $errors = [];
        $totalCredits = 0;
        
        // Récupérer toutes les UE obligatoires
        $level = $student->getLevel();
        $compulsoryUEs = $this->ueRepository->findCompulsoryByLevel($level);
        
        // Vérifier que toutes les UE obligatoires sont sélectionnées
        foreach ($compulsoryUEs as $compulsoryUE) {
            $isSelected = false;
            foreach ($selectedUEs as $selectedUE) {
                if ($selectedUE->getId() === $compulsoryUE->getId()) {
                    $isSelected = true;
                    break;
                }
            }
            
            if (!$isSelected) {
                $errors[] = "L'UE obligatoire " . $compulsoryUE->getName() . " (" . $compulsoryUE->getCode() . ") doit être sélectionnée.";
            }
        }
        
        // Calculer le nombre total de crédits
        foreach ($selectedUEs as $ue) {
            $totalCredits += $ue->getCredit();
        }
        
        // Minimum de crédits requis (exemple: 60 crédits par an)
        if ($totalCredits < 30) {
            $errors[] = "Vous devez sélectionner au moins 30 crédits au total. Actuellement, vous avez sélectionné $totalCredits crédits.";
        }
        
        return $errors;
    }
    
    /**
     * Obtient l'année académique actuelle (ex: "2024-2025")
     */
    public function getCurrentAcademicYear(): string
    {
        $currentMonth = (int) date('m');
        $currentYear = (int) date('Y');
        
        // Si on est entre janvier et août, on est dans l'année académique qui a commencé l'année précédente
        if ($currentMonth >= 1 && $currentMonth <= 8) {
            return ($currentYear - 1) . '-' . $currentYear;
        }
        
        // Sinon, on est dans l'année académique qui commence cette année
        return $currentYear . '-' . ($currentYear + 1);
    }
}