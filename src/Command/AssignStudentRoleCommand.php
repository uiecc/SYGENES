<?php
// src/Command/AssignStudentRoleCommand.php
namespace App\Command;

use App\Entity\Role;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:assign-student-role',
    description: 'Assigne le rôle ROLE_STUDENT à tous les étudiants',
)]
class AssignStudentRoleCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Récupérer le rôle ROLE_STUDENT
        $roleStudent = $this->entityManager->getRepository(Role::class)->findOneBy(['name' => 'ROLE_STUDENT']);
        
        if (!$roleStudent) {
            // Créer le rôle s'il n'existe pas
            $roleStudent = new Role();
            $roleStudent->setName('ROLE_STUDENT');
            $roleStudent->setDescription('Rôle pour les étudiants');
            $this->entityManager->persist($roleStudent);
            $this->entityManager->flush();
            
            $io->success('Rôle ROLE_STUDENT créé avec succès.');
        }

        // Récupérer tous les étudiants
        $students = $this->entityManager->getRepository(Student::class)->findAll();
        $count = 0;

        foreach ($students as $student) {
            // Vérifier si l'étudiant a déjà le rôle
            $hasRole = false;
            foreach ($student->getUserRoles() as $role) {
                if ($role->getName() === 'ROLE_STUDENT') {
                    $hasRole = true;
                    break;
                }
            }

            // Ajouter le rôle si nécessaire
            if (!$hasRole) {
                $student->addUserRole($roleStudent);
                $count++;
            }
        }

        // Sauvegarder les changements
        $this->entityManager->flush();

        $io->success(sprintf('%d étudiants ont été mis à jour avec le rôle ROLE_STUDENT.', $count));

        return Command::SUCCESS;
    }
}