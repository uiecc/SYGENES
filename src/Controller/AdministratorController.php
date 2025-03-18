<?php

namespace App\Controller;

use App\Entity\Administrator;
use App\Entity\Role;
use App\Form\AdministratorType;
use App\Repository\AdministratorRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class AdministratorController extends AbstractController
{
    #[Route('/', name: 'app_administrator_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $administrators = $entityManager
            ->getRepository(Administrator::class)
            ->findAll();

        return $this->render('administrator/index.html.twig', [
            'administrators' => $administrators,
        ]);
    }

    #[Route('/new', name: 'app_administrator_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        FileUploader $fileUploader
    ): Response {
        $administrator = new Administrator();
        $form = $this->createForm(AdministratorType::class, $administrator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var FileUploader $profilePhoto */
            if ($profilePhoto = $form->get('profilePhoto')->getData()) {
                $fileName = $fileUploader->upload($profilePhoto);
                $administrator->setProfilePhoto($fileName);
            }
            // Hash le mot de passe
            $hashedPassword = $passwordHasher->hashPassword(
                $administrator,
                $administrator->getPassword()
            );
            $administrator->setPassword($hashedPassword);

            // Ajout du rôle spécifique
            $roleManager = $entityManager
                ->getRepository(Role::class)
                ->findOneBy(['name' => 'ROLE_ADMIN']);

            if (!$roleManager) {
                $roleManager = new Role();
                $roleManager->setName('ROLE_ADMIN');
                $entityManager->persist($roleManager);
            }
            $administrator->addUserRole($roleManager);


            $entityManager->persist($administrator);
            $entityManager->flush();

            return $this->redirectToRoute('app_administrator_index');
        }

        return $this->render('administrator/new.html.twig', [
            'administrator' => $administrator,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_administrator_show', methods: ['GET'])]
    public function show(Administrator $administrator): Response
    {
        return $this->render('administrator/show.html.twig', [
            'administrator' => $administrator,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_administrator_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Administrator $administrator, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdministratorType::class, $administrator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_administrator_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administrator/edit.html.twig', [
            'administrator' => $administrator,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_administrator_delete', methods: ['POST'])]
    public function delete(Request $request, Administrator $administrator, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $administrator->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($administrator);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_administrator_index', [], Response::HTTP_SEE_OTHER);
    }
}
