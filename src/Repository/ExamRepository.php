<?php

namespace App\Repository;

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
