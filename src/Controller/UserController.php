<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\Role;
use App\Entity\Responsable;
use App\Entity\User;
use App\Form\StudentType;
use App\Form\ResponsableType;
use App\Form\UserProfileType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use App\Service\VerificationCodeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    #[Route('/register/student', name: 'app_register_student')]
    public function registerStudent(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash le mot de passe
            $hashedPassword = $passwordHasher->hashPassword($student, $student->getPassword());
            $student->setPassword($hashedPassword);

            // Ajouter rôle étudiant
            $roleStudent = $entityManager->getRepository(Role::class)->findOneBy(['name' => 'ROLE_STUDENT']);
            if (!$roleStudent) {
                $roleStudent = new Role();
                $roleStudent->setName('ROLE_STUDENT');
                $roleStudent->setDescription('Rôle pour les étudiants');
                $entityManager->persist($roleStudent);
            }
            $student->addUserRole($roleStudent); // Changé de addRole à addUserRole

            // Initialiser les valeurs par défaut
            $student->setIsActive(true);
            $student->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($student);
            $entityManager->flush();

            $this->addFlash('success', 'Compte étudiant créé avec succès!');
            return $this->redirectToRoute('app_register_student');
        }

        return $this->render('user/register_student.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/register/responsable', name: 'app_register_responsable')]
    public function registerResponsable(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $responsable = new Responsable();
        $form = $this->createForm(ResponsableType::class, $responsable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash le mot de passe
            $hashedPassword = $passwordHasher->hashPassword($responsable, $responsable->getPassword());
            $responsable->setPassword($hashedPassword);

            // Ajouter rôle responsable
            $roleResp = $entityManager->getRepository(Role::class)->findOneBy(['name' => 'ROLE_RESPONSABLE']);
            if (!$roleResp) {
                $roleResp = new Role();
                $roleResp->setName('ROLE_RESPONSABLE');
                $roleResp->setDescription('Rôle pour les responsables');
                $entityManager->persist($roleResp);
            }
            $responsable->addUserRole($roleResp); // Changé de addRole à addUserRole

            // Initialiser les valeurs par défaut
            $responsable->setIsActive(true);
            $responsable->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($responsable);
            $entityManager->flush();

            $this->addFlash('success', 'Compte responsable créé avec succès!');
            return $this->redirectToRoute('app_register_responsable');
        }

        return $this->render('user/register_responsable.html.twig', [
            'form' => $form->createView()
        ]);
    }



    // src/Controller/UserController.php

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // Rediriger si déjà authentifié complètement
        if ($this->getUser() && $request->getSession()->get('is_fully_authenticated')) {
            // Si l'utilisateur est un étudiant, le rediriger vers le dashboard étudiant
            if ($this->getUser() instanceof Student) {
                return $this->redirectToRoute('student_dashboard');
            }
            
            // Pour les autres utilisateurs, rediriger vers la page d'accueil
            return $this->redirectToRoute('app_home');
        }

        // Rediriger vers la vérification si en cours
        if ($request->getSession()->get('needs_2fa_verification')) {
            return $this->redirectToRoute('app_verify_code');
        }

        return $this->render('user/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    #[Route('/verify-code', name: 'app_verify_code', methods: ['GET', 'POST'])]
    public function verifyCode(Request $request, VerificationCodeService $codeService): Response
    {
        $session = $request->getSession();
    
        // Rediriger si déjà complètement authentifié
        if ($session->get('is_fully_authenticated')) {
            // Si l'utilisateur est un étudiant, le rediriger vers le dashboard étudiant
            if ($this->getUser() instanceof Student) {
                return $this->redirectToRoute('student_dashboard');
            }
            
            // Pour les autres utilisateurs, rediriger vers la page d'accueil
            return $this->redirectToRoute('app_home');
        }
    
        // Vérifier si en attente de vérification
        if (!$session->get('needs_2fa_verification')) {
            return $this->redirectToRoute('app_login');
        }
    
        if ($request->isMethod('POST')) {
            $code = $request->request->get('verification_code');
    
            if ($codeService->isCodeValid($this->getUser(), $code)) {
                $session->set('is_fully_authenticated', true);
                $session->remove('needs_2fa_verification');
                $session->remove('pending_user_id');
    
                // Si l'utilisateur est un étudiant, le rediriger vers le dashboard étudiant
                if ($this->getUser() instanceof Student) {
                    return $this->redirectToRoute('student_dashboard');
                }
                
                // Pour les autres utilisateurs, rediriger vers la page d'accueil
                return $this->redirectToRoute('app_home');
            }
    
            $this->addFlash('error', 'Code invalide ou expiré');
        }
    
        return $this->render('user/verify_code.html.twig');
    }
    #[Route('/resend-code', name: 'app_resend_code')]
    public function resendCode(Request $request, VerificationCodeService $codeService): Response
    {
        $user = $this->getUser();

        if (!$user || !$request->getSession()->get('needs_2fa_verification')) {
            return $this->redirectToRoute('app_login');
        }

        $codeService->generateCode($user);
        $this->addFlash('success', 'Un nouveau code a été envoyé');

        return $this->redirectToRoute('app_verify_code');
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Le contrôleur peut rester vide,
        // il sera intercepté par la clé logout du pare-feu dans security.yaml
    }


    #[Route('/profile', name: 'app_profile_show', methods: ['GET'])]
    public function show(): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        // Si l'utilisateur est un étudiant, le rediriger vers le profil étudiant
        if ($user instanceof Student) {
            return $this->redirectToRoute('student_profile');
        }

        return $this->render('user/show.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/profile/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        FileUploader $fileUploader
    ): Response {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        // Si l'utilisateur est un étudiant, le rediriger vers l'édition du profil étudiant
        if ($user instanceof Student) {
            return $this->redirectToRoute('student_profile_edit');
        }

        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer le changement de mot de passe
            if ($newPassword = $form->get('newPassword')->getData()) {
                // Vérifier l'ancien mot de passe
                if (!$passwordHasher->isPasswordValid($user, $form->get('currentPassword')->getData())) {
                    $form->get('currentPassword')->addError(new FormError('Mot de passe actuel incorrect'));
                    return $this->render('user/edit.html.twig', [
                        'form' => $form->createView()
                    ]);
                }
                $user->setPassword($passwordHasher->hashPassword($user, $newPassword));
            }

            // Gérer la photo de profil
            if ($photoFile = $form->get('profilePhoto')->getData()) {
                // Supprimer l'ancienne photo si elle existe
                if ($user->getProfilePhoto()) {
                    $oldFile = $fileUploader->getTargetDirectory() . '/' . $user->getProfilePhoto();
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }

                $fileName = $fileUploader->upload($photoFile);
                $user->setProfilePhoto($fileName);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès');

            return $this->redirectToRoute('app_profile_show');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}