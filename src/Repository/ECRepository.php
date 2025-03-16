<?php

namespace App\Repository;

use App\Entity\EC;
use App\Entity\Level;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EC>
 */
class ECRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EC::class);
    }

    /**
     * Compte le nombre total d'EC pour un niveau
     */
    public function countByLevel(Level $level): int
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->join('e.ue', 'u')
            ->join('u.semester', 's')
            ->where('s.level = :level')
            ->setParameter('level', $level)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Compte le nombre d'EC par semestre pour un niveau
     * 
     * @return array<int, int> Tableau associatif [id_semestre => nombre_d'EC]
     */
    public function countBySemester(Level $level): array
    {
        $result = $this->createQueryBuilder('e')
            ->select('s.id, COUNT(e.id) as ec_count')
            ->join('e.ue', 'u')
            ->join('u.semester', 's')
            ->where('s.level = :level')
            ->setParameter('level', $level)
            ->groupBy('s.id')
            ->getQuery()
            ->getResult();

        // Convertir en tableau associatif avec l'ID du semestre comme clé
        $ecsBySemester = [];
        foreach ($result as $item) {
            $ecsBySemester[$item['id']] = $item['ec_count'];
        }

        return $ecsBySemester;
    }

    /**
     * Trouve tous les EC pour un niveau donné, ordonnés par semestre et UE
     * 
     * @return array<int, EC>
     */
    public function findByLevelOrderedBySemesterAndUE(Level $level): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.ue', 'u')
            ->join('u.semester', 's')
            ->where('s.level = :level')
            ->setParameter('level', $level)
            ->orderBy('s.code', 'ASC')
            ->addOrderBy('u.code', 'ASC')
            ->addOrderBy('e.code', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve tous les EC pour une UE donnée
     * 
     * @return array<int, EC>
     */
    public function findByUE(int $ueId): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.ue = :ueId')
            ->setParameter('ueId', $ueId)
            ->orderBy('e.code', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte le nombre d'EC par UE pour un niveau
     * 
     * @return array<int, array>
     */
    public function countByUE(Level $level): array
    {
        return $this->createQueryBuilder('e')
            ->select('u.id, u.name, u.code, COUNT(e.id) as ec_count')
            ->join('e.ue', 'u')
            ->join('u.semester', 's')
            ->where('s.level = :level')
            ->setParameter('level', $level)
            ->groupBy('u.id')
            ->orderBy('u.code', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte le nombre d'EC pour les UE obligatoires d'un niveau
     */
    public function countByCompulsoryUE(Level $level): int
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->join('e.ue', 'u')
            ->join('u.semester', 's')
            ->where('s.level = :level')
            ->andWhere('u.isCompulsory = :compulsory')
            ->setParameter('level', $level)
            ->setParameter('compulsory', true)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Compte le nombre d'EC pour les UE optionnelles d'un niveau
     */
    public function countByOptionalUE(Level $level): int
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->join('e.ue', 'u')
            ->join('u.semester', 's')
            ->where('s.level = :level')
            ->andWhere('u.isCompulsory = :compulsory')
            ->setParameter('level', $level)
            ->setParameter('compulsory', false)
            ->getQuery()
            ->getSingleScalarResult();
    }

    // ECRepository.php
    public function countBySchool($school): int
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->join('e.ue', 'u')
            ->join('u.semester', 's')
            ->join('s.level', 'l')
            ->join('l.field', 'f')
            ->where('f.school = :school')
            ->setParameter('school', $school)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
