<?php

namespace App\Controller;

use App\Entity\EC;
use App\Form\ECType;
use App\Repository\ECRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ec')]
final class ECController extends AbstractController
{
    #[Route(name: 'app_ec_index', methods: ['GET'])]
    public function index(ECRepository $eCRepository): Response
    {
        return $this->render('ec/index.html.twig', [
            'ecs' => $eCRepository->findAll(), // Uniformiser les noms (ecs au lieu de e_cs)
        ]);
    }

    #[Route('/new', name: 'app_ec_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $eC = new EC();
        $form = $this->createForm(ECType::class, $eC);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($eC);
            $entityManager->flush();

            return $this->redirectToRoute('app_ec_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ec/new.html.twig', [
            'ec' => $eC, // Utiliser 'ec' au lieu de 'e_c'
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ec_show', methods: ['GET'])]
    public function show(EC $eC): Response
    {
        return $this->render('ec/show.html.twig', [
            'ec' => $eC, // Utiliser 'ec' au lieu de 'e_c'
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ec_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EC $eC, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ECType::class, $eC);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ec_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ec/edit.html.twig', [
            'ec' => $eC, // Utiliser 'ec' au lieu de 'e_c'
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ec_delete', methods: ['POST'])]
    public function delete(Request $request, EC $eC, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$eC->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($eC);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ec_index', [], Response::HTTP_SEE_OTHER);
    }
}