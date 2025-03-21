<?php

namespace App\Controller;

use App\Entity\AcademicYear;
use App\Entity\AnonymousCode;
use App\Entity\Exam;
use App\Entity\ExamGrade;
use App\Entity\Semester;
use App\Entity\Student;
use App\Repository\AcademicYearRepository;
use App\Repository\AnonymousCodeRepository;
use App\Repository\ExamGradeRepository;
use App\Repository\ExamRepository;
use App\Repository\SemesterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/exam-grade')]
#[IsGranted('ROLE_LEVEL_MANAGER')]
class ExamGradeController extends AbstractController
{
    #[Route('/enter/{id}', name: 'app_exam_grade_enter')]
    public function enter(Exam $exam, Request $request, EntityManagerInterface $entityManager, AnonymousCodeRepository $anonymousCodeRepository, ExamGradeRepository $examGradeRepository): Response
    {
        // Vérifier que l'examen appartient au niveau géré
        $user = $this->getUser();
        $levelManager = $user;
        $level = $levelManager->getLevel();
        
        if ($exam->getEc()->getUe()->getSemester()->getLevel()->getId() !== $level->getId()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit de saisir des notes pour cet examen.');
        }
        
        // Récupérer tous les codes anonymes pour cet examen (triés par code)
        $anonymousCodes = $anonymousCodeRepository->findBy(['exam' => $exam], ['code' => 'ASC']);
        
        // Récupérer les notes existantes
        $existingGrades = [];
        foreach ($anonymousCodes as $code) {
            $grade = $examGradeRepository->findOneBy(['anonymousCode' => $code]);
            if ($grade) {
                $existingGrades[$code->getId()] = $grade;
            }
        }
        
        // Traitement du formulaire
        if ($request->isMethod('POST')) {
            $grades = $request->request->all('grade');
            $comments = $request->request->all('comment');
            $isAutoSave = $request->request->get('auto_save') === '1';
            
            foreach ($grades as $codeId => $gradeValue) {
                if (trim($gradeValue) !== '') {
                    $code = $anonymousCodeRepository->find($codeId);
                    if (!$code) {
                        continue;
                    }
                    
                    $grade = isset($existingGrades[$codeId]) ? $existingGrades[$codeId] : new ExamGrade();
                    $grade->setAnonymousCode($code);
                    $grade->setGrade(floatval($gradeValue));
                    
                    if (isset($comments[$codeId])) {
                        $grade->setComment($comments[$codeId]);
                    }
                    
                    if (!isset($existingGrades[$codeId])) {
                        $grade->setCreatedAt(new \DateTimeImmutable());
                    }
                    
                    $grade->setUpdatedAt(new \DateTimeImmutable());
                    
                    $entityManager->persist($grade);
                    $existingGrades[$codeId] = $grade;
                }
            }
            
            $entityManager->flush();
            
            // Si c'est une sauvegarde automatique via AJAX, retourner un JSON
            if ($isAutoSave) {
                return new JsonResponse(['success' => true, 'message' => 'Notes sauvegardées avec succès']);
            }
            
            // Sinon, ajouter un message flash et rediriger
            $this->addFlash('success', 'Notes d\'examen enregistrées avec succès.');
            
            // Rediriger vers la même page si on est sur une page spécifique
            $page = $request->request->get('page', 1);
            if ($page > 1) {
                return $this->redirectToRoute('app_exam_grade_enter', [
                    'id' => $exam->getId(),
                    'page' => $page
                ]);
            }
            
            return $this->redirectToRoute('app_exam_grade_enter', ['id' => $exam->getId()]);
        }
        
        return $this->render('exam_grade/enter.html.twig', [
            'exam' => $exam,
            'anonymousCodes' => $anonymousCodes,
            'existingGrades' => $existingGrades,
        ]);
    }
    
    #[Route('/view-results/{id}', name: 'app_exam_grade_view_results')]
    public function viewResults(Exam $exam, ExamGradeRepository $examGradeRepository, AnonymousCodeRepository $anonymousCodeRepository): Response
    {
        // Vérifier que l'examen appartient au niveau géré
        $user = $this->getUser();
        $levelManager = $user;
        $level = $levelManager->getLevel();
        
        if ($exam->getEc()->getUe()->getSemester()->getLevel()->getId() !== $level->getId()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit de voir ces résultats.');
        }
        
        // Récupérer tous les codes avec leurs notes
        $results = $examGradeRepository->findResultsByExam($exam);
        
        return $this->render('exam_grade/view_results.html.twig', [
            'exam' => $exam,
            'results' => $results
        ]);
    }
    
    #[Route('/generate-pv/{id}', name: 'app_exam_grade_generate_pv')]
    public function generatePV(Exam $exam, ExamGradeRepository $examGradeRepository): Response
    {
        // Vérifier que l'examen appartient au niveau géré
        $user = $this->getUser();
        $levelManager = $user;
        $level = $levelManager->getLevel();
        
        if ($exam->getEc()->getUe()->getSemester()->getLevel()->getId() !== $level->getId()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit de générer ce PV.');
        }
        
        // Récupérer tous les résultats avec les noms des étudiants
        $results = $examGradeRepository->findCompleteResultsByExam($exam);
        
        // Récupérer les statistiques de l'examen
        $statistics = $examGradeRepository->getExamStatistics($exam);
        
        // Générer le PDF
        $html = $this->renderView('exam_grade/pv.html.twig', [
            'exam' => $exam,
            'results' => $results,
            'level' => $level,
            'statistics' => $statistics,
            'generatedDate' => new \DateTime(),
        ]);
        
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $filename = 'pv_examen_' . $exam->getEc()->getCode() . '_' . date('Y-m-d') . '.pdf';
        
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]
        );
    }
    

    #[Route('/generate-complete-pv', name: 'app_exam_grade_generate_complete_pv')]
    public function generateCompletePV(
        EntityManagerInterface $entityManager, 
        ExamRepository $examRepository,
        ExamGradeRepository $examGradeRepository,
        AcademicYearRepository $academicYearRepository,
        SemesterRepository $semesterRepository
    ): Response
    {
        // Récupérer le niveau géré par l'utilisateur connecté
        $user = $this->getUser();
        $levelManager = $user;
        $level = $levelManager->getLevel();
        
        // Récupérer le semestre et l'année académique en cours
        $semester = $semesterRepository->findCurrentSemester();
        $academicYear = $academicYearRepository->findCurrentYear();
        
        // Fallback si les méthodes personnalisées échouent
        if (!$semester) {
            // Récupérer le premier semestre du niveau
            $semester = $entityManager->getRepository(Semester::class)
                ->findOneBy(['level' => $level], ['code' => 'ASC']);
            
            if (!$semester) {
                $this->addFlash('error', 'Aucun semestre trouvé pour ce niveau.');
                return $this->redirectToRoute('app_exam_index');
            }
        }
        
        if (!$academicYear) {
            // Récupérer la dernière année académique
            $academicYear = $entityManager->getRepository(AcademicYear::class)
                ->findOneBy([], ['endDate' => 'DESC']);
            
            if (!$academicYear) {
                $this->addFlash('error', 'Aucune année académique trouvée.');
                return $this->redirectToRoute('app_exam_index');
            }
        }
        
        // Récupérer tous les examens pour ce niveau dans l'année académique trouvée
        $exams = $examRepository->findByLevelAndYear($level, $academicYear);
        
        if (empty($exams)) {
            $this->addFlash('warning', 'Aucun examen trouvé pour ce niveau dans l\'année académique en cours.');
            return $this->redirectToRoute('app_exam_index');
        }
        
        // Organiser les données par UE et EC
        $resultsByUE = [];
        
        foreach ($exams as $exam) {
            $ue = $exam->getEc()->getUe();
            $ec = $exam->getEc();
            
            // Récupérer les résultats pour cet examen
            $examResults = $examGradeRepository->findCompleteResultsByExam($exam);
            $statistics = $examGradeRepository->getExamStatistics($exam);
            
            // Organiser par UE puis par EC
            if (!isset($resultsByUE[$ue->getId()])) {
                $resultsByUE[$ue->getId()] = [
                    'ue' => $ue,
                    'ecs' => []
                ];
            }
            
            if (!isset($resultsByUE[$ue->getId()]['ecs'][$ec->getId()])) {
                $resultsByUE[$ue->getId()]['ecs'][$ec->getId()] = [
                    'ec' => $ec,
                    'exams' => []
                ];
            }
            
            $resultsByUE[$ue->getId()]['ecs'][$ec->getId()]['exams'][] = [
                'exam' => $exam,
                'results' => $examResults,
                'statistics' => $statistics
            ];
        }
        
        // Récupérer tous les étudiants du niveau
        $students = $entityManager->getRepository(Student::class)->findBy(['level' => $level], ['lastName' => 'ASC', 'firstName' => 'ASC']);
        
        // Générer le PDF
        $html = $this->renderView('exam_grade/complete_pv.html.twig', [
            'level' => $level,
            'resultsByUE' => $resultsByUE,
            'students' => $students,
            'academicYear' => $academicYear,
            'semester' => $semester,
            'generatedDate' => new \DateTime(),
        ]);
        
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape'); // Format paysage pour plus d'espace pour les colonnes
        $dompdf->render();
        
        $filename = 'pv_complet_' . $level->getCode() . '_' . date('Y-m-d') . '.pdf';
        
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]
        );
    
    }}