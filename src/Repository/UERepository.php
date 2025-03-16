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

    /**
     * Compte le nombre total d'UE pour un niveau
     */
    public function countByLevel(Level $level): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->join('u.semester', 's')
            ->where('s.level = :level')
            ->setParameter('level', $level)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Compte le nombre d'UE obligatoires pour un niveau
     */
    public function countCompulsoryByLevel(Level $level): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->join('u.semester', 's')
            ->where('s.level = :level')
            ->andWhere('u.isCompulsory = :compulsory')
            ->setParameter('level', $level)
            ->setParameter('compulsory', true)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Compte le nombre d'UE optionnelles pour un niveau
     */
    public function countOptionalByLevel(Level $level): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->join('u.semester', 's')
            ->where('s.level = :level')
            ->andWhere('u.isCompulsory = :compulsory')
            ->setParameter('level', $level)
            ->setParameter('compulsory', false)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Compte le nombre d'UE par semestre pour un niveau
     */
    public function countBySemester(Level $level): array
    {
        return $this->createQueryBuilder('u')
            ->select('s.id, s.name, s.code, COUNT(u.id) as ue_count')
            ->join('u.semester', 's')
            ->where('s.level = :level')
            ->setParameter('level', $level)
            ->groupBy('s.id')
            ->orderBy('s.code', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // UERepository.php
    public function countBySchool($school): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->join('u.semester', 's')
            ->join('s.level', 'l')
            ->join('l.field', 'f')
            ->where('f.school = :school')
            ->setParameter('school', $school)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
