<?php

namespace App\Controller;

use App\Entity\Student;
use App\Repository\NoteRepository;
use App\Service\AcademicYearService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/results')]
class StudentResultsController extends AbstractController
{
    private AcademicYearService $academicYearService;

    public function __construct(AcademicYearService $academicYearService)
    {
        $this->academicYearService = $academicYearService;
    }

    #[Route('/student/{student}', name: 'app_student_results')]
    public function studentResults(Student $student, NoteRepository $noteRepository): Response
    {
        $user = $this->getUser();
        $hasAccess = false;
        
        // Vérifier que l'utilisateur a le droit d'accéder aux notes
        if ($user instanceof Student && $user->getId() === $student->getId()) {
            // L'étudiant peut voir ses propres notes
            $hasAccess = true;
        } elseif ($this->isGranted('ROLE_SCHOOL_MANAGER')) {
            // Le gestionnaire d'école peut voir toutes les notes
            $hasAccess = true;
        } elseif ($this->isGranted('ROLE_FIELD_MANAGER') && method_exists($user, 'getFieldManager') && $user->getFieldManager()) {
            // Le gestionnaire de filière peut voir les notes des étudiants de sa filière
            $field = $user->getFieldManager()->getField();
            $hasAccess = ($student->getLevel()->getField()->getId() === $field->getId());
        } elseif ($this->isGranted('ROLE_LEVEL_MANAGER') && method_exists($user, 'getLevelManager') && $user->getLevelManager()) {
            // Le gestionnaire de niveau peut voir les notes des étudiants de son niveau
            $level = $user->getLevelManager()->getLevel();
            $hasAccess = ($student->getLevel()->getId() === $level->getId());
        } elseif (method_exists($user, 'getTeacher') && $user->getTeacher()) {
            // L'enseignant peut voir les notes des étudiants auxquels il enseigne
            $teacher = $user->getTeacher();
            // Cette vérification nécessiterait une requête plus complexe, mais pour simplifier:
            // On vérifie si l'enseignant enseigne à un EC du niveau de l'étudiant
            $hasAccess = true; // À améliorer dans une version ultérieure
        }
        
        if (!$hasAccess) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit de consulter ces notes.');
        }
        
        $academicYear = $this->academicYearService->getCurrentAcademicYear();
        
        // Récupérer toutes les notes de l'étudiant pour l'année académique en cours
        $notes = $noteRepository->findBy([
            'student' => $student,
            'academicYear' => $academicYear
        ]);
        
        // Organiser les notes par Semestre -> UE -> EC
        $notesBySemester = [];
        foreach ($notes as $note) {
            $ec = $note->getEc();
            $ue = $ec->getUe();
            $semester = $ue->getSemester();
            $semesterId = $semester->getId();
            
            // Initialiser l'entrée pour ce semestre si elle n'existe pas encore
            if (!isset($notesBySemester[$semesterId])) {
                $notesBySemester[$semesterId] = [
                    'semester' => $semester,
                    'ues' => []
                ];
            }
            
            // Initialiser l'entrée pour cette UE si elle n'existe pas encore
            if (!isset($notesBySemester[$semesterId]['ues'][$ue->getId()])) {
                $notesBySemester[$semesterId]['ues'][$ue->getId()] = [
                    'ue' => $ue,
                    'notes' => []
                ];
            }
            
            // Ajouter la note à l'UE correspondante
            $notesBySemester[$semesterId]['ues'][$ue->getId()]['notes'][] = $note;
        }
        
        return $this->render('note/student_results.html.twig', [
            'student' => $student,
            'notesBySemester' => $notesBySemester,
            'academicYear' => $academicYear,
        ]);
    }
    
    #[Route('/my-results', name: 'app_my_results')]
    #[IsGranted('ROLE_STUDENT')]
    public function myResults(NoteRepository $noteRepository): Response
    {
        // Rediriger vers ses propres résultats
        $student = $this->getUser();
        
        return $this->redirectToRoute('app_student_results', [
            'student' => $student->getId()
        ]);
    }
}