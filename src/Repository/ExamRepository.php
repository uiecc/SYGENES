<?php

namespace App\Repository;

use App\Entity\AcademicYear;
use App\Entity\Exam;
use App\Entity\Level;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Exam>
 */
class ExamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exam::class);
    }


        /**
     * Trouve tous les examens associés à un niveau spécifique.
     * 
     * @param Level $level Le niveau pour lequel on recherche les examens
     * @return array<int, Exam> Les examens associés au niveau
     */
    public function findExamsByLevel(Level $level): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.ec', 'ec')
            ->join('ec.ue', 'ue')
            ->join('ue.semester', 's')
            ->join('s.level', 'l')
            ->andWhere('l.id = :levelId')
            ->setParameter('levelId', $level->getId())
            ->orderBy('e.examDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

        /**
     * Trouve tous les examens pour un niveau et une année académique spécifiques.
     * 
     * Cette méthode construit une requête personnalisée pour trouver les examens
     * qui sont associés à un niveau via les relations EC->UE->Semester->Level
     * et qui appartiennent à une année académique donnée.
     * 
     * @param Level $level Le niveau
     * @param AcademicYear $academicYear L'année académique
     * @return array<int, Exam> Les examens correspondants
     */
    public function findByLevelAndYear(Level $level, AcademicYear $academicYear): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.ec', 'ec')
            ->join('ec.ue', 'ue')
            ->join('ue.semester', 's')
            ->join('s.level', 'l')
            ->andWhere('l.id = :levelId')
            ->andWhere('e.academicYear = :academicYear')
            ->andWhere('e.status = :status') // Seulement les examens terminés/corrigés
            ->setParameter('levelId', $level->getId())
            ->setParameter('academicYear', $academicYear)
            ->setParameter('status', 'GRADED') // Statut "Corrigé"
            ->orderBy('ue.code', 'ASC')
            ->addOrderBy('ec.code', 'ASC')
            ->getQuery()
            ->getResult();
    }
    
    /**
     * Trouve tous les examens de type normal pour un niveau et une année académique.
     */
    public function findNormalExamsByLevelAndYear(Level $level, AcademicYear $academicYear): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.ec', 'ec')
            ->join('ec.ue', 'ue')
            ->join('ue.semester', 's')
            ->join('s.level', 'l')
            ->andWhere('l.id = :levelId')
            ->andWhere('e.academicYear = :academicYear')
            ->andWhere('e.type = :type')
            ->andWhere('e.status = :status')
            ->setParameter('levelId', $level->getId())
            ->setParameter('academicYear', $academicYear)
            ->setParameter('type', 'NORMAL')
            ->setParameter('status', 'GRADED')
            ->orderBy('ue.code', 'ASC')
            ->addOrderBy('ec.code', 'ASC')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Exam[] Returns an array of Exam objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Exam
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
