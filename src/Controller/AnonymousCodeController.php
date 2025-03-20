<?php

namespace App\Controller;

use App\Entity\AnonymousCode;
use App\Entity\Exam;
use App\Entity\Level;
use App\Entity\Student;
use App\Form\BulkAnonymousCodeGeneratorType;
use App\Repository\AnonymousCodeRepository;
use App\Repository\ExamRepository;
use App\Repository\LevelRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\ByteString;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/anonymous-code')]
#[IsGranted('ROLE_LEVEL_MANAGER')]
class AnonymousCodeController extends AbstractController
{
    #[Route('/', name: 'app_anonymous_code_index')]
    public function index(AnonymousCodeRepository $anonymousCodeRepository): Response
    {
        // Récupérer l'utilisateur et le niveau géré
        $user = $this->getUser();
        $levelManager = $user;
        $level = $levelManager->getLevel();
        
        // Récupérer les codes anonymes pour ce niveau
        $anonymousCodes = $anonymousCodeRepository->findByLevel($level);
        
        // Organiser les codes par examen
        $codesByExam = [];
        foreach ($anonymousCodes as $code) {
            $examId = $code->getExam()->getId();
            if (!isset($codesByExam[$examId])) {
                $codesByExam[$examId] = [
                    'exam' => $code->getExam(),
                    'codes' => []
                ];
            }
            $codesByExam[$examId]['codes'][] = $code;
        }
        
        return $this->render('anonymous_code/index.html.twig', [
            'codesByExam' => $codesByExam
        ]);
    }
    
    #[Route('/bulk-generate', name: 'app_anonymous_code_bulk_generate')]
    public function bulkGenerate(
        Request $request,
        EntityManagerInterface $entityManager,
        ExamRepository $examRepository,
        LevelRepository $levelRepository
    ): Response {
        // Récupérer l'utilisateur et le niveau géré
        $user = $this->getUser();
        $levelManager = $user;
        $level = $levelManager->getLevel();
        
        // Récupérer les examens de ce niveau
        $exams = $examRepository->findByLevel($level);
        
        // Créer le formulaire
        $form = $this->createForm(BulkAnonymousCodeGeneratorType::class, null, [
            'exams' => $exams,
            'levels' => [$level]
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $exam = $data['exam'];
            $codeLength = $form->get('codeLength')->getData();
            $codeType = $form->get('codeType')->getData();
            
            // Vérifier que l'examen appartient bien au niveau géré
            if ($exam->getEc()->getUe()->getSemester()->getLevel()->getId() !== $level->getId()) {
                throw $this->createAccessDeniedException('Vous n\'avez pas le droit de générer des codes anonymes pour cet examen.');
            }
            
            // Générer les codes anonymes
            $this->generateCodesForExam($entityManager, $exam, $level, $codeLength, $codeType);
            
            $this->addFlash('success', 'Les codes anonymes ont été générés avec succès.');
            return $this->redirectToRoute('app_anonymous_code_index');
        }
        
        return $this->render('anonymous_code/bulk_generate.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    #[Route('/generate/{id}', name: 'app_anonymous_code_generate')]
    public function generate(
        Exam $exam,
        EntityManagerInterface $entityManager,
        StudentRepository $studentRepository
    ): Response {
        // Vérifier que l'examen appartient au niveau géré
        $user = $this->getUser();
        $levelManager = $user;
        $level = $levelManager->getLevel();
        
        if ($exam->getEc()->getUe()->getSemester()->getLevel()->getId() !== $level->getId()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit de générer des codes anonymes pour cet examen.');
        }
        
        // Générer les codes avec les paramètres par défaut
        $this->generateCodesForExam($entityManager, $exam, $level);
        
        $this->addFlash('success', 'Codes anonymes générés avec succès.');
        return $this->redirectToRoute('app_exam_index');
    }
    
    #[Route('/print/{id}', name: 'app_anonymous_code_print')]
    public function printCodes(Exam $exam, AnonymousCodeRepository $anonymousCodeRepository): Response
    {
        // Vérifier les droits d'accès
        $user = $this->getUser();
        $levelManager = $user;
        $level = $levelManager->getLevel();
        
        if ($exam->getEc()->getUe()->getSemester()->getLevel()->getId() !== $level->getId()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit d\'accéder à ces codes anonymes.');
        }
        
        // Récupérer tous les codes anonymes pour cet examen
        $anonymousCodes = $anonymousCodeRepository->findBy(['exam' => $exam], ['code' => 'ASC']);
        
        // Générer le PDF avec les noms et codes (pour l'administration)
        $html = $this->renderView('anonymous_code/admin_list.html.twig', [
            'exam' => $exam,
            'anonymousCodes' => $anonymousCodes,
        ]);
        
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $filename = 'codes_anonymes_admin_' . $exam->getEc()->getCode() . '.pdf';
        
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]
        );
    }
    
    #[Route('/print-for-corrections/{id}', name: 'app_anonymous_code_print_for_corrections')]
    public function printCodesForCorrections(Exam $exam, AnonymousCodeRepository $anonymousCodeRepository): Response
    {
        // Vérifier les droits d'accès
        $user = $this->getUser();
        $levelManager = $user;
        $level = $levelManager->getLevel();
        
        if ($exam->getEc()->getUe()->getSemester()->getLevel()->getId() !== $level->getId()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit d\'accéder à ces codes anonymes.');
        }
        
        // Récupérer tous les codes anonymes pour cet examen
        $anonymousCodes = $anonymousCodeRepository->findBy(['exam' => $exam], ['code' => 'ASC']);
        
        // Générer le PDF avec seulement les codes (pour les correcteurs)
        $html = $this->renderView('anonymous_code/correction_list.html.twig', [
            'exam' => $exam,
            'anonymousCodes' => $anonymousCodes,
        ]);
        
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $filename = 'codes_anonymes_correcteurs_' . $exam->getEc()->getCode() . '.pdf';
        
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]
        );
    }
    
    /**
     * Génère des codes anonymes pour tous les étudiants d'un niveau pour un examen donné
     */
    private function generateCodesForExam(
        EntityManagerInterface $entityManager,
        Exam $exam,
        Level $level,
        int $codeLength = 6,
        string $codeType = 'ALPHANUMERIC'
    ): void {
        // Récupérer les étudiants du niveau
        $students = $entityManager->getRepository(Student::class)->findBy(['level' => $level]);
        
        // Générer un code anonyme pour chaque étudiant
        foreach ($students as $student) {
            // Vérifier si l'étudiant a déjà un code pour cet examen
            $existingCode = $entityManager->getRepository(AnonymousCode::class)->findOneBy([
                'student' => $student,
                'exam' => $exam
            ]);
            
            if (!$existingCode) {
                $anonymousCode = new AnonymousCode();
                $anonymousCode->setStudent($student);
                $anonymousCode->setExam($exam);
                $anonymousCode->setCode($this->generateUniqueCode($entityManager, $exam, $codeLength, $codeType));
                $anonymousCode->setCreatedAt(new \DateTimeImmutable());
                
                $entityManager->persist($anonymousCode);
            }
        }
        
        $entityManager->flush();
    }
    
    /**
     * Génère un code unique pour un examen
     */
    private function generateUniqueCode(
        EntityManagerInterface $entityManager,
        Exam $exam,
        int $length = 6,
        string $type = 'ALPHANUMERIC'
    ): string {
        $codeExists = true;
        $code = '';
        
        while ($codeExists) {
            switch ($type) {
                case 'NUMERIC':
                    $code = ByteString::fromRandom($length, '0123456789')->toString();
                    break;
                case 'ALPHABETIC':
                    $code = ByteString::fromRandom($length, 'ABCDEFGHJKLMNPQRSTUVWXYZ')->toString();
                    break;
                case 'ALPHANUMERIC':
                default:
                    $code = ByteString::fromRandom($length, '0123456789ABCDEFGHJKLMNPQRSTUVWXYZ')->toString();
                    break;
            }
            
            // Vérifier que le code n'existe pas déjà pour cet examen
            $existing = $entityManager->getRepository(AnonymousCode::class)->findOneBy([
                'code' => $code,
                'exam' => $exam
            ]);
            
            $codeExists = ($existing !== null);
        }
        
        return $code;
    }
}