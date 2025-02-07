<?php

namespace App\Controller;

use App\Entity\FieldManager;
use App\Entity\Role;
use App\Form\FieldManagerType;
use App\Repository\FieldManagerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

// src/Controller/FieldManagerController.php
#[Route('/field/manager')]
class FieldManagerController extends AbstractController
{
    #[Route('/', name: 'app_field_manager_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $fieldManagers = $entityManager
            ->getRepository(FieldManager::class)
            ->findAll();

        return $this->render('field_manager/index.html.twig', [
            'field_managers' => $fieldManagers,
        ]);
    }

    #[Route('/new', name: 'app_field_manager_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $fieldManager = new FieldManager();
        $form = $this->createForm(FieldManagerType::class, $fieldManager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword(
                $fieldManager,
                $fieldManager->getPassword()
            );
            $fieldManager->setPassword($hashedPassword);

            // Ajout du rôle spécifique
            $roleManager = $entityManager
                ->getRepository(Role::class)
                ->findOneBy(['name' => 'ROLE_FIELD_MANAGER']);
            
            if (!$roleManager) {
                $roleManager = new Role();
                $roleManager->setName('ROLE_FIELD_MANAGER');
                $entityManager->persist($roleManager);
            }
            
            $fieldManager->addUserRole($roleManager);

            $entityManager->persist($fieldManager);
            $entityManager->flush();

            $this->addFlash('success', 'Le responsable de filière a été créé avec succès.');
            return $this->redirectToRoute('app_field_manager_index');
        }

        return $this->render('field_manager/new.html.twig', [
            'field_manager' => $fieldManager,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_field_manager_show', methods: ['GET'])]
    public function show(FieldManager $fieldManager): Response
    {
        return $this->render('field_manager/show.html.twig', [
            'field_manager' => $fieldManager,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_field_manager_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FieldManager $fieldManager, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FieldManagerType::class, $fieldManager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_field_manager_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('field_manager/edit.html.twig', [
            'field_manager' => $fieldManager,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_field_manager_delete', methods: ['POST'])]
    public function delete(Request $request, FieldManager $fieldManager, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fieldManager->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($fieldManager);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_field_manager_index', [], Response::HTTP_SEE_OTHER);
    }
}
