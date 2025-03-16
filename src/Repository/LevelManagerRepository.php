<?php

namespace App\Repository;

use App\Entity\LevelManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LevelManager>
 */
class LevelManagerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LevelManager::class);
    }

    // LevelManagerRepository.php
    public function countBySchool($school): int
    {
        return $this->createQueryBuilder('lm')
            ->select('COUNT(lm.id)')
            ->join('lm.level', 'l')
            ->join('l.field', 'f')
            ->where('f.school = :school')
            ->setParameter('school', $school)
            ->getQuery()
            ->getSingleScalarResult();
    }
    //    /**
    //     * @return LevelManager[] Returns an array of LevelManager objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?LevelManager
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
