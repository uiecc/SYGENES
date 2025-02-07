<?php
namespace App\Controller;

use App\Entity\Student;
use App\Entity\Role;
use App\Entity\Responsable;
use App\Form\StudentType;
use App\Form\ResponsableType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

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
    }}