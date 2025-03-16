<?php

namespace App\Controller;

use App\Entity\LevelManager;
use App\Entity\Role;
use App\Form\LevelManagerType;
use App\Repository\LevelManagerRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/level-manager')]
#[IsGranted('ROLE_FIELD_MANAGER')]
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
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        FileUploader $fileUploader
    ): Response
    {
        $levelManager = new LevelManager();
        
        // Récupérer ou créer le rôle ROLE_LEVEL_MANAGER
        $roleManager = $entityManager
            ->getRepository(Role::class)
            ->findOneBy(['name' => 'ROLE_LEVEL_MANAGER']);
        
        if (!$roleManager) {
            $roleManager = new Role();
            $roleManager->setName('ROLE_LEVEL_MANAGER');
            $entityManager->persist($roleManager);
        }
        
        // Assigner le rôle au LevelManager
        $levelManager->addUserRole($roleManager);
        
        $form = $this->createForm(LevelManagerType::class, $levelManager);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Hasher le mot de passe
            $plainPassword = $levelManager->getPassword();
            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword(
                    $levelManager,
                    $plainPassword
                );
                $levelManager->setPassword($hashedPassword);
            }
            
            // Gérer l'upload de la photo de profil
            $profilePhotoFile = $form->get('profilePhotoFile')->getData();
            if ($profilePhotoFile) {
                $profilePhotoFilename = $fileUploader->upload($profilePhotoFile);
                $levelManager->setProfilePhoto($profilePhotoFilename);
            }
            
            $entityManager->persist($levelManager);
            $entityManager->flush();
    
            $this->addFlash('success', 'Le responsable de niveau a été créé avec succès.');
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
    public function edit(
        Request $request, 
        LevelManager $levelManager, 
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        FileUploader $fileUploader
    ): Response
    {
        $originalPassword = $levelManager->getPassword();
        
        $form = $this->createForm(LevelManagerType::class, $levelManager, [
            'is_edit' => true,
            'level_manager' => $levelManager,
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si le mot de passe a été modifié
            $newPassword = $levelManager->getPassword();
            if ($newPassword && $newPassword !== $originalPassword) {
                // Hasher le nouveau mot de passe
                $hashedPassword = $passwordHasher->hashPassword(
                    $levelManager,
                    $newPassword
                );
                $levelManager->setPassword($hashedPassword);
            } else {
                // Restaurer l'ancien mot de passe si aucun nouveau n'a été fourni
                $levelManager->setPassword($originalPassword);
            }
            
            // Gérer l'upload de la photo de profil
            $profilePhotoFile = $form->get('profilePhotoFile')->getData();
            if ($profilePhotoFile) {
                // Supprimer l'ancienne photo si elle existe
                $oldPhotoFilename = $levelManager->getProfilePhoto();
                if ($oldPhotoFilename && $oldPhotoFilename !== 'default.jpg') {
                    $oldPhotoPath = $fileUploader->getTargetDirectory() . '/' . $oldPhotoFilename;
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }
                
                $profilePhotoFilename = $fileUploader->upload($profilePhotoFile);
                $levelManager->setProfilePhoto($profilePhotoFilename);
            }
            
            $entityManager->flush();

            $this->addFlash('success', 'Le responsable de niveau a été modifié avec succès.');
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
            $this->addFlash('success', 'Le responsable de niveau a été supprimé avec succès.');
        }

        return $this->redirectToRoute('app_level_manager_index', [], Response::HTTP_SEE_OTHER);
    }
}