<?php

namespace App\Repository;

use App\Entity\FieldManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FieldManager>
 */
class FieldManagerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FieldManager::class);
    }

    // FieldManagerRepository.php
    public function findBySchool($school): array
    {
        return $this->createQueryBuilder('fm')
            ->join('fm.field', 'f')
            ->where('f.school = :school')
            ->setParameter('school', $school)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return FieldManager[] Returns an array of FieldManager objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?FieldManager
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
