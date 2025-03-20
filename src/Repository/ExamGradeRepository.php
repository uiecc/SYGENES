<?php

namespace App\Repository;

use App\Entity\ExamGrade;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExamGrade>
 */
class ExamGradeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExamGrade::class);
    }

    /**
     * @return ExamGrade[] Returns an array of ExamGrade objects
     */
    public function findResultsByExam($exam): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.anonymousCode', 'a')
            ->andWhere('a.exam = :exam')
            ->setParameter('exam', $exam)
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    //    public function findOneBySomeField($value): ?ExamGrade
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
