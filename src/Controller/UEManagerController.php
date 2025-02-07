<?php

namespace App\Controller;

use App\Entity\UEManager;
use App\Form\UEManagerType;
use App\Repository\UEManagerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/u/e/manager')]
final class UEManagerController extends AbstractController
{
    #[Route(name: 'app_u_e_manager_index', methods: ['GET'])]
    public function index(UEManagerRepository $uEManagerRepository): Response
    {
        return $this->render('ue_manager/index.html.twig', [
            'u_e_managers' => $uEManagerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_u_e_manager_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $uEManager = new UEManager();
        $form = $this->createForm(UEManagerType::class, $uEManager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($uEManager);
            $entityManager->flush();

            return $this->redirectToRoute('app_u_e_manager_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ue_manager/new.html.twig', [
            'u_e_manager' => $uEManager,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_u_e_manager_show', methods: ['GET'])]
    public function show(UEManager $uEManager): Response
    {
        return $this->render('ue_manager/show.html.twig', [
            'u_e_manager' => $uEManager,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_u_e_manager_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UEManager $uEManager, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UEManagerType::class, $uEManager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_u_e_manager_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ue_manager/edit.html.twig', [
            'u_e_manager' => $uEManager,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_u_e_manager_delete', methods: ['POST'])]
    public function delete(Request $request, UEManager $uEManager, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$uEManager->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($uEManager);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_u_e_manager_index', [], Response::HTTP_SEE_OTHER);
    }
}
