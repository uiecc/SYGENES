<?php

namespace App\Controller;

use App\Repository\FieldRepository;
use App\Repository\FieldManagerRepository;
use App\Repository\LevelRepository;
use App\Repository\LevelManagerRepository;
use App\Repository\StudentRepository;
use App\Repository\UERepository;
use App\Repository\ECRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;

#[Route('/managerSchool')]
#[IsGranted('ROLE_SCHOOL_MANAGER')]
class SchoolManagerDashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'school_manager_dashboard')]
    public function dashboard(
        Request $request,
        FieldRepository $fieldRepository,
        FieldManagerRepository $fieldManagerRepository,
        LevelRepository $levelRepository,
        LevelManagerRepository $levelManagerRepository,
        StudentRepository $studentRepository,
        UERepository $ueRepository,
        ECRepository $ecRepository
    ): Response {
        $user = $this->getUser();
        
        // Si l'utilisateur n'est pas connecté ou n'a pas le bon rôle
        if (!$user || !$this->isGranted('ROLE_SCHOOL_MANAGER')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas les droits nécessaires.');
        }
        
        // Récupérer l'école associée à l'utilisateur
        $school = $user->getSchool(); // Cela suppose que votre objet User a une méthode getSchool()
        
        if (!$school) {
            $this->addFlash('error', 'Aucune école n\'est associée à votre compte.');
            return $this->redirectToRoute('app_home');
        }
        
        // Récupérer les filières de l'école
        $fields = $fieldRepository->findBy(['school' => $school]);
        
        // Récupérer les responsables de filière
        $fieldManagers = $fieldManagerRepository->findBySchool($school);
        
        // Récupérer les niveaux par filière
        $levelsByField = [];
        $totalLevels = 0;
        
        foreach ($fields as $field) {
            $levels = $levelRepository->findBy(['field' => $field]);
            $levelsByField[$field->getId()] = $levels;
            $totalLevels += count($levels);
        }
        
        // Récupérer le nombre total d'étudiants par filière
        $studentsByField = [];
        $totalStudents = 0;
        
        foreach ($fields as $field) {
            $count = $studentRepository->countByField($field);
            $studentsByField[$field->getId()] = $count;
            $totalStudents += $count;
        }
        
        // Récupérer les statistiques sur les UE et EC
        $totalUEs = $ueRepository->countBySchool($school);
        $totalECs = $ecRepository->countBySchool($school);
        
        // Récupérer le nombre de responsables de niveau
        $levelManagersCount = $levelManagerRepository->countBySchool($school);
        
        // Statistiques
        $stats = [
            'fields_count' => count($fields),
            'total_levels' => $totalLevels,
            'total_students' => $totalStudents,
            'field_managers_count' => count($fieldManagers),
            'level_managers_count' => $levelManagersCount,
            'total_ues' => $totalUEs,
            'total_ecs' => $totalECs
        ];
        
        return $this->render('school_manager_dashboard/index.html.twig', [
            'user' => $user,
            'school' => $school,
            'fields' => $fields,
            'fieldManagers' => $fieldManagers,
            'levelsByField' => $levelsByField,
            'studentsByField' => $studentsByField,
            'stats' => $stats
        ]);
    }
    
    #[Route('/fields', name: 'school_manager_fields')]
    public function fields(
        Request $request,
        FieldRepository $fieldRepository
    ): Response {
        $user = $this->getUser();
        
        if (!$user || !$this->isGranted('ROLE_SCHOOL_MANAGER')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas les droits nécessaires.');
        }
        
        $school = $user->getSchool();
        
        if (!$school) {
            $this->addFlash('error', 'Aucune école n\'est associée à votre compte.');
            return $this->redirectToRoute('app_home');
        }
        
        $fields = $fieldRepository->findBy(['school' => $school]);
        
        return $this->render('school_manager/fields.html.twig', [
            'user' => $user,
            'school' => $school,
            'fields' => $fields
        ]);
    }
    
    #[Route('/levels', name: 'school_manager_levels')]
    public function levels(
        Request $request,
        LevelRepository $levelRepository,
        FieldRepository $fieldRepository
    ): Response {
        $user = $this->getUser();
        
        if (!$user || !$this->isGranted('ROLE_SCHOOL_MANAGER')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas les droits nécessaires.');
        }
        
        $school = $user->getSchool();
        
        if (!$school) {
            $this->addFlash('error', 'Aucune école n\'est associée à votre compte.');
            return $this->redirectToRoute('app_home');
        }
        
        $fields = $fieldRepository->findBy(['school' => $school]);
        
        $levels = [];
        foreach ($fields as $field) {
            $fieldLevels = $levelRepository->findBy(['field' => $field]);
            foreach ($fieldLevels as $level) {
                $levels[] = $level;
            }
        }
        
        return $this->render('school_manager/levels.html.twig', [
            'user' => $user,
            'school' => $school,
            'levels' => $levels,
            'fields' => $fields
        ]);
    }
    
    #[Route('/students', name: 'school_manager_students')]
    public function students(
        Request $request,
        StudentRepository $studentRepository
    ): Response {
        $user = $this->getUser();
        
        if (!$user || !$this->isGranted('ROLE_SCHOOL_MANAGER')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas les droits nécessaires.');
        }
        
        $school = $user->getSchool();
        
        if (!$school) {
            $this->addFlash('error', 'Aucune école n\'est associée à votre compte.');
            return $this->redirectToRoute('app_home');
        }
        
        $students = $studentRepository->findBySchool($school);
        
        return $this->render('school_manager/students.html.twig', [
            'user' => $user,
            'school' => $school,
            'students' => $students
        ]);
    }
    
    #[Route('/statistics', name: 'school_manager_statistics')]
    public function statistics(
        Request $request,
        FieldRepository $fieldRepository,
        StudentRepository $studentRepository,
        UERepository $ueRepository,
        ECRepository $ecRepository
    ): Response {
        $user = $this->getUser();
        
        if (!$user || !$this->isGranted('ROLE_SCHOOL_MANAGER')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas les droits nécessaires.');
        }
        
        $school = $user->getSchool();
        
        if (!$school) {
            $this->addFlash('error', 'Aucune école n\'est associée à votre compte.');
            return $this->redirectToRoute('app_home');
        }
        
        $fields = $fieldRepository->findBy(['school' => $school]);
        
        // Statistiques sur les étudiants par filière
        $studentsByField = [];
        foreach ($fields as $field) {
            $studentsByField[$field->getId()] = $studentRepository->countByField($field);
        }
        
        // Statistiques sur les UE et EC
        $stats = [
            'total_ues' => $ueRepository->countBySchool($school),
            'total_ecs' => $ecRepository->countBySchool($school),
            'total_students' => array_sum($studentsByField)
        ];
        
        return $this->render('school_manager/statistics.html.twig', [
            'user' => $user,
            'school' => $school,
            'fields' => $fields,
            'studentsByField' => $studentsByField,
            'stats' => $stats
        ]);
    }
    
}