<?php

namespace App\Controller;

use App\Entity\FieldManager;
use App\Entity\Student;
use App\Entity\Level;
use App\Entity\LevelManager;
use App\Repository\LevelRepository;
use App\Repository\LevelManagerRepository;
use App\Repository\StudentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/field-manager')]
#[IsGranted('ROLE_FIELD_MANAGER')]
class FieldManagerDashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'field_manager_dashboard')]
    public function dashboard(
        LevelRepository $levelRepository,
        StudentRepository $studentRepository,
        LevelManagerRepository $levelManagerRepository
    ): Response {
        /** @var FieldManager $fieldManager */
        $fieldManager = $this->getUser();
        $field = $fieldManager->getField();
        
        if (!$field) {
            $this->addFlash('error', 'Aucune filière n\'est associée à votre compte.');
            return $this->redirectToRoute('app_error');
        }
        
        // Récupérer les niveaux de la filière
        $levels = $levelRepository->findBy(['field' => $field]);
        
        // Récupérer le nombre d'étudiants par niveau
        $studentsByLevel = [];
        $totalStudents = 0;
        
        foreach ($levels as $level) {
            $count = $studentRepository->countByLevel($level);
            $studentsByLevel[$level->getId()] = $count;
            $totalStudents += $count;
        }
        
        // Récupérer les levelManagers pour chaque niveau
        $levelManagers = [];
        foreach ($levels as $level) {
            $manager = $levelManagerRepository->findOneBy(['level' => $level]);
            if ($manager) {
                $levelManagers[$level->getId()] = $manager;
            }
        }
        
        // Statistiques
        $stats = [
            'total_levels' => count($levels),
            'total_students' => $totalStudents,
            'level_managers_count' => count($levelManagers),
            'levels_without_manager' => count($levels) - count($levelManagers)
        ];
        
        return $this->render('field_manager_dashboard/dashboard.html.twig', [
            'fieldManager' => $fieldManager,
            'field' => $field,
            'levels' => $levels,
            'studentsByLevel' => $studentsByLevel,
            'levelManagers' => $levelManagers,
            'stats' => $stats
        ]);
    }
}