<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\SchoolManager;
use App\Form\SchoolManagerType;
use App\Repository\SchoolManagerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/school-manager')]
class SchoolManagerController extends AbstractController
{
    #[Route('/', name: 'app_school_manager_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $schoolManagers = $entityManager
            ->getRepository(SchoolManager::class)
            ->findAll();

        return $this->render('school_manager/index.html.twig', [
            'school_managers' => $schoolManagers,
        ]);
    }

    #[Route('/new', name: 'app_school_manager_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $schoolManager = new SchoolManager();
        $form = $this->createForm(SchoolManagerType::class, $schoolManager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hashage du mot de passe
            $hashedPassword = $passwordHasher->hashPassword(
                $schoolManager,
                $schoolManager->getPassword()
            );
            $schoolManager->setPassword($hashedPassword);

            // Définition des rôles
            $roleManager = $entityManager
                ->getRepository(Role::class)
                ->findOneBy(['name' => 'ROLE_SCHOOL_MANAGER']);
            
            if (!$roleManager) {
                $roleManager = new Role();
                $roleManager->setName('ROLE_SCHOOL_MANAGER');
                $entityManager->persist($roleManager);
            }
            
            $schoolManager->addUserRole($roleManager);

            $entityManager->persist($schoolManager);
            $entityManager->flush();

            $this->addFlash('success', 'Le responsable d\'école a été créé avec succès.');
            return $this->redirectToRoute('app_school_manager_index');
        }

        return $this->render('school_manager/new.html.twig', [
            'school_manager' => $schoolManager,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_school_manager_show', methods: ['GET'])]
    public function show(SchoolManager $schoolManager): Response
    {
        return $this->render('school_manager/show.html.twig', [
            'school_manager' => $schoolManager,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_school_manager_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SchoolManager $schoolManager, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SchoolManagerType::class, $schoolManager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_school_manager_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('school_manager/edit.html.twig', [
            'school_manager' => $schoolManager,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_school_manager_delete', methods: ['POST'])]
    public function delete(Request $request, SchoolManager $schoolManager, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$schoolManager->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($schoolManager);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_school_manager_index', [], Response::HTTP_SEE_OTHER);
    }
}
