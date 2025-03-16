<?php

namespace App\Controller;

use App\Entity\UE;
use App\Entity\LevelManager;
use App\Form\UEType;
use App\Repository\UERepository;
use App\Repository\SemesterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('ue')]
#[IsGranted('ROLE_LEVEL_MANAGER')]
final class UEController extends AbstractController
{
    #[Route(name: 'app_u_e_index', methods: ['GET'])]
    public function index(UERepository $uERepository, SemesterRepository $semesterRepository): Response
    {
        /** @var LevelManager $levelManager */
        $levelManager = $this->getUser();
        
        if (!$levelManager) {
            throw $this->createAccessDeniedException('Vous devez être responsable de niveau pour accéder à cette page.');
        }
        
        $level = $levelManager->getLevel();
        
        // Récupérer les semestres du niveau
        $semesters = $semesterRepository->findBy(['level' => $level]);
        
        // Récupérer les UE pour ces semestres
        $ues = $uERepository->findBy(['semester' => $semesters]);
        
        return $this->render('ue/index.html.twig', [
            'u_es' => $ues,
            'level' => $level,
        ]);
    }

    #[Route('/new', name: 'app_u_e_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var LevelManager $levelManager */
        $levelManager = $this->getUser();
        
        if (!$levelManager instanceof LevelManager) {
            throw $this->createAccessDeniedException('Vous devez être responsable de niveau pour accéder à cette page.');
        }
        
        $level = $levelManager->getLevel();
        
        if (!$level) {
            $this->addFlash('error', 'Vous n\'êtes pas associé à un niveau. Veuillez contacter un administrateur.');
            return $this->redirectToRoute('dashboard');
        }
        
        $uE = new UE();
        $form = $this->createForm(UEType::class, $uE, [
            'level' => $level
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérification supplémentaire que le semestre appartient bien au niveau du responsable
            $semester = $uE->getSemester();
            if ($semester && $semester->getLevel()->getId() !== $level->getId()) {
                $this->addFlash('error', 'Le semestre sélectionné n\'appartient pas à votre niveau.');
                return $this->render('ue/new.html.twig', [
                    'u_e' => $uE,
                    'form' => $form,
                ]);
            }
            
            $entityManager->persist($uE);
            $entityManager->flush();
            
            $this->addFlash('success', 'L\'UE a été créée avec succès.');
            return $this->redirectToRoute('app_u_e_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ue/new.html.twig', [
            'u_e' => $uE,
            'form' => $form,
            'level' => $level
        ]);
    }

    #[Route('/{id}', name: 'app_u_e_show', methods: ['GET'])]
    public function show(UE $uE): Response
    {
        // Vérifier que l'UE appartient au niveau du responsable
        $this->checkUEAccess($uE);
        
        return $this->render('ue/show.html.twig', [
            'u_e' => $uE,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_u_e_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UE $uE, EntityManagerInterface $entityManager): Response
    {
        /** @var LevelManager $levelManager */
        $levelManager = $this->getUser();
        
        if (!$levelManager instanceof LevelManager) {
            throw $this->createAccessDeniedException('Vous devez être responsable de niveau pour accéder à cette page.');
        }
        
        $level = $levelManager->getLevel();
        
        // Vérifier si l'UE appartient au niveau du responsable
        $ueSemester = $uE->getSemester();
        if (!$ueSemester || $ueSemester->getLevel()->getId() !== $level->getId()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette UE car elle n\'appartient pas à votre niveau.');
        }
        
        $form = $this->createForm(UEType::class, $uE, [
            'level' => $level
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérification supplémentaire que le semestre appartient bien au niveau du responsable
            $semester = $uE->getSemester();
            if ($semester && $semester->getLevel()->getId() !== $level->getId()) {
                $this->addFlash('error', 'Le semestre sélectionné n\'appartient pas à votre niveau.');
                return $this->render('ue/edit.html.twig', [
                    'u_e' => $uE,
                    'form' => $form,
                ]);
            }
            
            $entityManager->flush();
            
            $this->addFlash('success', 'L\'UE a été modifiée avec succès.');
            return $this->redirectToRoute('app_u_e_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ue/edit.html.twig', [
            'u_e' => $uE,
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}', name: 'app_u_e_delete', methods: ['POST'])]
    public function delete(Request $request, UE $uE, EntityManagerInterface $entityManager): Response
    {
        // Vérifier que l'UE appartient au niveau du responsable
        $this->checkUEAccess($uE);
        
        if ($this->isCsrfTokenValid('delete'.$uE->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($uE);
            $entityManager->flush();
            $this->addFlash('success', 'L\'UE a été supprimée avec succès.');
        }

        return $this->redirectToRoute('app_u_e_index', [], Response::HTTP_SEE_OTHER);
    }
    
    /**
     * Vérifie que l'UE appartient au niveau du responsable
     */
    private function checkUEAccess(UE $ue): void
    {
        /** @var LevelManager $levelManager */
        $levelManager = $this->getUser();
        
        if (!$levelManager) {
            throw $this->createAccessDeniedException('Vous devez être responsable de niveau pour accéder à cette page.');
        }
        
        $level = $levelManager->getLevel();
        $ueSemesterLevel = $ue->getSemester()->getLevel();
        
        if ($ueSemesterLevel->getId() !== $level->getId()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette UE car elle n\'appartient pas à votre niveau.');
        }
    }
}