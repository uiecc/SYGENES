<?php
// src/Controller/StudentDashboardController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Student;
use App\Entity\StudentUE;
use App\Entity\UE;
use App\Form\AcademicRegistrationType;
use App\Form\StudentProfileEditType;
use App\Form\StudentProfileType;
use App\Repository\StudentUERepository;
use App\Repository\UERepository;
use App\Service\AcademicRegistrationService;
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
    public function academicRegistration(
        Request $request,
        AcademicRegistrationService $registrationService,
        EntityManagerInterface $entityManager,
        UERepository $ueRepository
    ): Response {
        /** @var Student $student */
        $student = $this->getUser();

        if (!$student instanceof Student) {
            throw $this->createAccessDeniedException('Vous devez être connecté en tant qu\'étudiant pour accéder à cette page.');
        }

        $academicYear = $registrationService->getCurrentAcademicYear();
        $level = $student->getLevel();

        // Récupérer toutes les UE disponibles pour ce niveau
        $availableUEs = $ueRepository->findByLevelOrderedBySemester($level);

        // Récupérer les UE obligatoires
        $compulsoryUEs = $ueRepository->findCompulsoryByLevel($level);
        $compulsoryUEIds = array_map(fn($ue) => $ue->getId(), $compulsoryUEs);

        // Récupérer les UE optionnelles
        $optionalUEs = array_filter($availableUEs, function ($ue) use ($compulsoryUEIds) {
            return !in_array($ue->getId(), $compulsoryUEIds);
        });

        // Inscription automatique aux UE obligatoires
        $registeredUEs = $registrationService->getRegisteredUEs($student, $academicYear);
        $registeredUEIds = array_map(fn($ue) => $ue->getId(), $registeredUEs);

        if (empty($registeredUEs)) {
            $registrationService->registerStudentToCompulsoryUEs($student, $academicYear);
            $this->addFlash('info', 'Vous avez été automatiquement inscrit aux UE obligatoires.');
            return $this->redirectToRoute('student_academic_registration');
        }

        // Si le formulaire est soumis
        if ($request->isMethod('POST')) {
            // Récupérer les IDs des UE sélectionnées
            $selectedUEIds = $request->request->all('ues') ?? [];

            // Ajouter les UE obligatoires aux sélections
            $selectedUEIds = array_merge($selectedUEIds, $compulsoryUEIds);

            // Supprimer les doublons
            $selectedUEIds = array_unique($selectedUEIds);

            // Récupérer les objets UE correspondants
            $selectedUEs = [];
            foreach ($selectedUEIds as $ueId) {
                $ue = $ueRepository->find($ueId);
                if ($ue) {
                    $selectedUEs[] = $ue;
                }
            }

            // Valider la sélection
            $validationErrors = $registrationService->validateUESelection($student, $selectedUEs, $academicYear);

            if (empty($validationErrors)) {
                // Mettre à jour les inscriptions

                // 1. Supprimer toutes les inscriptions optionnelles existantes
                foreach ($student->getStudentUEs() as $studentUE) {
                    if (
                        $studentUE->getAcademicYear() === $academicYear &&
                        !in_array($studentUE->getUe()->getId(), $compulsoryUEIds)
                    ) {
                        $entityManager->remove($studentUE);
                    }
                }

                // 2. Ajouter les nouvelles inscriptions
                foreach ($selectedUEs as $ue) {
                    if (!in_array($ue->getId(), $registeredUEIds)) {
                        $studentUE = new StudentUE();
                        $studentUE->setStudent($student);
                        $studentUE->setUe($ue);
                        $studentUE->setAcademicYear($academicYear);
                        $studentUE->setRegisteredAt(new \DateTimeImmutable());
                        $studentUE->setStatus('registered');
                        $entityManager->persist($studentUE);
                    }
                }

                $entityManager->flush();

                $this->addFlash('success', 'Votre inscription académique a été mise à jour avec succès.');
                return $this->redirectToRoute('student_academic_registration');
            } else {
                foreach ($validationErrors as $error) {
                    $this->addFlash('error', $error);
                }
            }
        }

        return $this->render('student/academic_registration.html.twig', [
            'student' => $student,
            'academicYear' => $academicYear,
            'compulsoryUEs' => $compulsoryUEs,
            'optionalUEs' => $optionalUEs,
            'registeredUEIds' => $registeredUEIds,
        ]);
    }

    #[Route('/registration-pdf/{academicYear}', name: 'student_registration_pdf')]
    public function registrationPdf(
        string $academicYear,
        StudentUERepository $studentUERepository
    ): Response {
        /** @var Student $student */
        $student = $this->getUser();

        if (!$student instanceof Student) {
            throw $this->createAccessDeniedException('Vous devez être connecté en tant qu\'étudiant pour accéder à cette page.');
        }

        // Récupérer toutes les inscriptions aux UE de l'étudiant pour l'année académique spécifiée
        $studentUEs = $studentUERepository->findByStudentAndAcademicYear($student, $academicYear);

        // Si l'étudiant n'a pas d'inscriptions, rediriger vers la page d'inscription
        if (count($studentUEs) === 0) {
            $this->addFlash('warning', 'Vous n\'avez pas encore d\'inscription pour cette année académique.');
            return $this->redirectToRoute('student_academic_registration');
        }

        // Générer le HTML pour le PDF
        $html = $this->renderView('student/registration_pdf.html.twig', [
            'student' => $student,
            'academicYear' => $academicYear,
            'studentUEs' => $studentUEs,
        ]);

        // Configuration de Dompdf
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true); // Pour charger les images
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('chroot', $this->getParameter('kernel.project_dir') . '/public'); // Pour accéder aux fichiers locaux

        // Créer l'instance Dompdf
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);

        // Configuration du format de papier et de l'orientation
        $dompdf->setPaper('A4', 'portrait');

        // Rendre le PDF
        $dompdf->render();

        // Générer un nom de fichier pour le PDF
        $filename = sprintf(
            'inscription_academique_%s_%s.pdf',
            $student->getMatricule(),
            str_replace('/', '-', $academicYear)
        );

        // Retourner la réponse avec le PDF
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );
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
    ): Response {
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
    }
}
