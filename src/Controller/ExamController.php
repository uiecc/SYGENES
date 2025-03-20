<?php

namespace App\Controller;

use App\Entity\EC;
use App\Entity\Exam;
use App\Form\ExamType;
use App\Repository\ECRepository;
use App\Repository\ExamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/exam')]
#[IsGranted('ROLE_LEVEL_MANAGER')]
class ExamController extends AbstractController
{
    #[Route('/', name: 'app_exam_index')]
    public function index(ExamRepository $examRepository): Response
    {
        // Pour un gestionnaire de niveau, afficher uniquement les examens de son niveau
        $user = $this->getUser();
        $level = $user->getLevel();
        
        // Utiliser une méthode personnalisée dans le repository pour trouver les examens par niveau
        $exams = $examRepository->findExamsByLevel($level);
        
        return $this->render('exam/index.html.twig', [
            'exams' => $exams,
        ]);
    }
    
    #[Route('/new', name: 'app_exam_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, ECRepository $ecRepository): Response
    {
        $exam = new Exam();
        
        // Limiter les EC disponibles à ceux du niveau géré
        $user = $this->getUser();
        $level = $user->getLevel();
        $ecs = $ecRepository->findByLevel($level);
        
        $form = $this->createForm(ExamType::class, $exam, [
            'ecs' => $ecs
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Assurez-vous que le coefficient est basé sur les crédits de l'EC
            if ($exam->getEc()) {
                $exam->setWeight($exam->getEc()->getCredit());
            }
            
            $entityManager->persist($exam);
            $entityManager->flush();
            
            $this->addFlash('success', 'Examen créé avec succès.');
            return $this->redirectToRoute('app_exam_index');
        }
        
        return $this->render('exam/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/{id}/edit', name: 'app_exam_edit')]
    public function edit(Request $request, Exam $exam, EntityManagerInterface $entityManager, ECRepository $ecRepository): Response
    {
        // Vérifier que l'examen appartient au niveau géré
        $user = $this->getUser();
        $level = $user->getLevel();
        
        if ($exam->getEc()->getUe()->getSemester()->getLevel()->getId() !== $level->getId()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas le droit de modifier cet examen.');
        }
        
        $ecs = $ecRepository->findByLevel($level);
        
        $form = $this->createForm(ExamType::class, $exam, [
            'ecs' => $ecs
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Assurez-vous que le coefficient est basé sur les crédits de l'EC
            if ($exam->getEc()) {
                $exam->setWeight($exam->getEc()->getCredit());
            }
            
            $entityManager->flush();
            
            $this->addFlash('success', 'Examen modifié avec succès.');
            return $this->redirectToRoute('app_exam_index');
        }
        
        return $this->render('exam/edit.html.twig', [
            'exam' => $exam,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/get-ec-credit/{id}', name: 'app_exam_get_ec_credit')]
    public function getEcCredit(EC $ec): Response
    {
        // Endpoint AJAX pour récupérer le nombre de crédits d'un EC
        return $this->json([
            'credit' => $ec->getCredit()
        ]);
    }
}