<?php

namespace App\Controller;

use App\Entity\LevelManager;
use App\Form\LevelManagerType;
use App\Repository\LevelManagerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/level/manager')]
final class LevelManagerController extends AbstractController
{
    #[Route(name: 'app_level_manager_index', methods: ['GET'])]
    public function index(LevelManagerRepository $levelManagerRepository): Response
    {
        return $this->render('level_manager/index.html.twig', [
            'level_managers' => $levelManagerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_level_manager_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $levelManager = new LevelManager();
        $form = $this->createForm(LevelManagerType::class, $levelManager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($levelManager);
            $entityManager->flush();

            return $this->redirectToRoute('app_level_manager_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('level_manager/new.html.twig', [
            'level_manager' => $levelManager,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_level_manager_show', methods: ['GET'])]
    public function show(LevelManager $levelManager): Response
    {
        return $this->render('level_manager/show.html.twig', [
            'level_manager' => $levelManager,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_level_manager_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LevelManager $levelManager, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LevelManagerType::class, $levelManager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_level_manager_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('level_manager/edit.html.twig', [
            'level_manager' => $levelManager,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_level_manager_delete', methods: ['POST'])]
    public function delete(Request $request, LevelManager $levelManager, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$levelManager->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($levelManager);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_level_manager_index', [], Response::HTTP_SEE_OTHER);
    }
}
