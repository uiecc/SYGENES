<?php

namespace App\Repository;

use App\Entity\UE;
use App\Entity\Level;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UE>
 *
 * @method UE|null find($id, $lockMode = null, $lockVersion = null)
 * @method UE|null findOneBy(array $criteria, array $orderBy = null)
 * @method UE[]    findAll()
 * @method UE[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UERepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UE::class);
    }

    /**
     * Trouve toutes les UE pour un niveau donné, ordonnées par semestre
     * 
     * @param Level $level
     * @return UE[]
     */
    public function findByLevelOrderedBySemester(Level $level): array
    {
        return $this->createQueryBuilder('u')
            ->join('u.semester', 's')
            ->where('s.level = :level')
            ->setParameter('level', $level)
            ->orderBy('s.code', 'ASC')
            ->addOrderBy('u.code', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve toutes les UE obligatoires pour un niveau donné
     * 
     * @param Level $level
     * @return UE[]
     */
    public function findCompulsoryByLevel(Level $level): array
    {
        return $this->createQueryBuilder('u')
            ->join('u.semester', 's')
            ->where('s.level = :level')
            ->andWhere('u.isCompulsory = :compulsory')
            ->setParameter('level', $level)
            ->setParameter('compulsory', true)
            ->orderBy('s.code', 'ASC')
            ->addOrderBy('u.code', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve toutes les UE optionnelles pour un niveau donné
     * 
     * @param Level $level
     * @return UE[]
     */
    public function findOptionalByLevel(Level $level): array
    {
        return $this->createQueryBuilder('u')
            ->join('u.semester', 's')
            ->where('s.level = :level')
            ->andWhere('u.isCompulsory = :compulsory')
            ->setParameter('level', $level)
            ->setParameter('compulsory', false)
            ->orderBy('s.code', 'ASC')
            ->addOrderBy('u.code', 'ASC')
            ->getQuery()
            ->getResult();
    }
}