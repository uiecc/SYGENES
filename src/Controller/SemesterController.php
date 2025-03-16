<?php

namespace App\Controller;

use App\Entity\LevelManager;
use App\Entity\Semester;
use App\Form\SemesterType;
use App\Repository\SemesterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/semester')]
final class SemesterController extends AbstractController
{
    #[Route(name: 'app_semester_index', methods: ['GET'])]
    public function index(SemesterRepository $semesterRepository): Response
    {
        return $this->render('semester/index.html.twig', [
            'semesters' => $semesterRepository->findAll(),
        ]);
    }

    // SemesterController.php
    #[Route('/new', name: 'app_semester_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var LevelManager $levelManager */
        $levelManager = $this->getUser();

        if (!$levelManager instanceof LevelManager) {
            throw $this->createAccessDeniedException('Vous devez être responsable de niveau pour accéder à cette page.');
        }

        $semester = new Semester();
        // Pré-remplir le niveau avec celui du LevelManager
        $semester->setLevel($levelManager->getLevel());

        $form = $this->createForm(SemesterType::class, $semester, [
            'levelManager' => $levelManager
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérification supplémentaire que le niveau est bien celui du LevelManager
            if ($semester->getLevel()->getId() !== $levelManager->getLevel()->getId()) {
                throw $this->createAccessDeniedException('Vous ne pouvez créer des semestres que pour votre propre niveau.');
            }

            $entityManager->persist($semester);
            $entityManager->flush();

            $this->addFlash('success', 'Le semestre a été créé avec succès.');
            return $this->redirectToRoute('app_semester_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('semester/new.html.twig', [
            'semester' => $semester,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_semester_show', methods: ['GET'])]
    public function show(Semester $semester): Response
    {
        return $this->render('semester/show.html.twig', [
            'semester' => $semester,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_semester_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Semester $semester, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SemesterType::class, $semester);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_semester_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('semester/edit.html.twig', [
            'semester' => $semester,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_semester_delete', methods: ['POST'])]
    public function delete(Request $request, Semester $semester, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $semester->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($semester);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_semester_index', [], Response::HTTP_SEE_OTHER);
    }
}
