<?php
// src/Controller/StudentDashboardController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Student;
use App\Form\StudentProfileEditType;
use App\Form\StudentProfileType;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/student')]
#[IsGranted('ROLE_STUDENT')]
class StudentDashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'student_dashboard')]
    public function dashboard(): Response
    {
        /** @var Student $student */
        $student = $this->getUser();

        if (!$student instanceof Student) {
            throw $this->createAccessDeniedException('Vous devez être connecté en tant qu\'étudiant pour accéder à cette page.');
        }

        // Récupérer les informations de l'étudiant nécessaires au dashboard
        $level = $student->getLevel();

        return $this->render('student/dashboard.html.twig', [
            'student' => $student,
            'level' => $level,
        ]);
    }

    #[Route('/academic-registration', name: 'student_academic_registration')]
    public function academicRegistration(): Response
    {
        /** @var Student $student */
        $student = $this->getUser();

        return $this->render('student/academic_registration.html.twig', [
            'student' => $student,
        ]);
    }

    #[Route('/grades', name: 'student_grades')]
    public function viewGrades(): Response
    {
        /** @var Student $student */
        $student = $this->getUser();

        // Exemple de données fictives pour les notes
        $grades = [
            [
                'course' => 'Mathématiques',
                'code' => 'MATH101',
                'credits' => 4,
                'grade' => 15.5,
                'status' => 'Validé'
            ],
            [
                'course' => 'Physique',
                'code' => 'PHYS101',
                'credits' => 3,
                'grade' => 14.0,
                'status' => 'Validé'
            ],
        ];

        return $this->render('student/grades.html.twig', [
            'student' => $student,
            'grades' => $grades,
        ]);
    }

    #[Route('/profile', name: 'student_profile')]
    public function showProfile(): Response
    {
        /** @var Student $student */
        $student = $this->getUser();

        if (!$student instanceof Student) {
            throw $this->createAccessDeniedException('Vous devez être connecté en tant qu\'étudiant pour accéder à cette page.');
        }

        return $this->render('student/profile.html.twig', [
            'student' => $student,
        ]);
    }
// Modifier la méthode editProfile pour ajouter le paramètre FileUploader
#[Route('/profile/edit', name: 'student_profile_edit')]
public function editProfile(
    Request $request, 
    EntityManagerInterface $entityManager, 
    UserPasswordHasherInterface $passwordHasher,
    FileUploader $fileUploader
): Response
{
    /** @var Student $student */
    $student = $this->getUser();
    
    if (!$student instanceof Student) {
        throw $this->createAccessDeniedException('Vous devez être connecté en tant qu\'étudiant pour accéder à cette page.');
    }
    
    $form = $this->createForm(StudentProfileEditType::class, $student);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        // Gérer le téléchargement de la photo de profil
        $profilePhotoFile = $form->get('profilePhotoFile')->getData();
        
        if ($profilePhotoFile) {
            // Si l'étudiant a déjà une photo autre que la photo par défaut, supprimer l'ancienne
            $oldPhotoFilename = $student->getProfilePhoto();
            if ($oldPhotoFilename && $oldPhotoFilename !== 'default.jpg') {
                $oldPhotoPath = $fileUploader->getTargetDirectory() . '/' . $oldPhotoFilename;
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }
            
            // Générer un nom unique pour la nouvelle photo et la déplacer
            $newFilename = $fileUploader->upload($profilePhotoFile);
            
            // Mettre à jour l'entité avec le nouveau nom de fichier
            $student->setProfilePhoto($newFilename);
        }
        
        // Gérer le changement de mot de passe (code existant)
        $passwordUpdated = false;
        if ($form->has('newPassword') && $form->get('newPassword')->getData()) {
            // ... code existant pour la gestion du mot de passe
        }
        
        // Persistez les changements
        $entityManager->flush();
        
        if ($passwordUpdated) {
            $this->addFlash('success', 'Votre profil et votre mot de passe ont été mis à jour avec succès.');
        } else {
            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');
        }
        
        return $this->redirectToRoute('student_profile');
    }
    
    return $this->render('student/profile_edit.html.twig', [
        'form' => $form->createView(),
        'student' => $student
    ]);
}}
