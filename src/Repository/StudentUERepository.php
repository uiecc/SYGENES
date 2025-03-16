<?php

namespace App\Repository;

use App\Entity\StudentUE;
use App\Entity\Student;
use App\Entity\Level;
use App\Entity\UE;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StudentUE>
 *
 * @method StudentUE|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudentUE|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudentUE[]    findAll()
 * @method StudentUE[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentUERepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentUE::class);
    }

    /**
     * Trouve toutes les inscriptions d'un étudiant pour une année académique donnée
     */
    public function findByStudentAndAcademicYear(Student $student, string $academicYear): array
    {
        return $this->createQueryBuilder('su')
            ->where('su.student = :student')
            ->andWhere('su.academicYear = :academicYear')
            ->setParameter('student', $student)
            ->setParameter('academicYear', $academicYear)
            ->getQuery()
            ->getResult();
    }

    /**
     * Vérifie si un étudiant est inscrit à une UE spécifique pour une année académique
     */
    public function isStudentRegisteredToUE(Student $student, UE $ue, string $academicYear): bool
    {
        $result = $this->createQueryBuilder('su')
            ->select('COUNT(su.id)')
            ->where('su.student = :student')
            ->andWhere('su.ue = :ue')
            ->andWhere('su.academicYear = :academicYear')
            ->setParameter('student', $student)
            ->setParameter('ue', $ue)
            ->setParameter('academicYear', $academicYear)
            ->getQuery()
            ->getSingleScalarResult();
            
        return (int)$result > 0;
    }

    /**
     * Trouve tous les étudiants inscrits à une UE pour une année académique
     */
    public function findStudentsByUEAndAcademicYear(UE $ue, string $academicYear): array
    {
        $results = $this->createQueryBuilder('su')
            ->join('su.student', 's')
            ->where('su.ue = :ue')
            ->andWhere('su.academicYear = :academicYear')
            ->setParameter('ue', $ue)
            ->setParameter('academicYear', $academicYear)
            ->getQuery()
            ->getResult();
            
        // Extraire les étudiants des résultats
        $students = [];
        foreach ($results as $studentUE) {
            $students[] = $studentUE->getStudent();
        }
        
        return $students;
    }

    /**
     * Trouve toutes les UE auxquelles un étudiant est inscrit pour une année académique
     */
    public function findUEsByStudentAndAcademicYear(Student $student, string $academicYear): array
    {
        $results = $this->createQueryBuilder('su')
            ->join('su.ue', 'u')
            ->where('su.student = :student')
            ->andWhere('su.academicYear = :academicYear')
            ->setParameter('student', $student)
            ->setParameter('academicYear', $academicYear)
            ->getQuery()
            ->getResult();
            
        // Extraire les UE des résultats
        $ues = [];
        foreach ($results as $studentUE) {
            $ues[] = $studentUE->getUe();
        }
        
        return $ues;
    }
    
    /**
     * Compte le nombre de crédits auxquels un étudiant est inscrit pour une année académique
     */
    public function countCreditsForStudent(Student $student, string $academicYear): int
    {
        $result = $this->createQueryBuilder('su')
            ->select('SUM(u.credit)')
            ->join('su.ue', 'u')
            ->where('su.student = :student')
            ->andWhere('su.academicYear = :academicYear')
            ->setParameter('student', $student)
            ->setParameter('academicYear', $academicYear)
            ->getQuery()
            ->getSingleScalarResult();
            
        return (int)$result;
    }
    
    /**
     * Compte le nombre d'étudiants inscrits à une UE
     */
    public function countStudentsForUE(UE $ue, string $academicYear): int
    {
        $result = $this->createQueryBuilder('su')
            ->select('COUNT(DISTINCT su.student)')
            ->where('su.ue = :ue')
            ->andWhere('su.academicYear = :academicYear')
            ->setParameter('ue', $ue)
            ->setParameter('academicYear', $academicYear)
            ->getQuery()
            ->getSingleScalarResult();
            
        return (int)$result;
    }
}