<?php

namespace App\Repository;

use App\Entity\AcademicYear;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AcademicYear>
 */
class AcademicYearRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AcademicYear::class);
    }

    /**
     * Trouve l'année académique actuelle
     * 
     * @return AcademicYear|null L'année académique actuelle ou null si aucune n'est trouvée
     */
    public function findCurrentYear(): ?AcademicYear
    {
        $currentDate = new \DateTime();
        
        // D'abord, essayer de trouver une année académique qui englobe la date actuelle
        $academicYear = $this->createQueryBuilder('a')
            ->where('a.startDate <= :currentDate')
            ->andWhere('a.endDate >= :currentDate')
            ->setParameter('currentDate', $currentDate)
            ->getQuery()
            ->getOneOrNullResult();
        
        // Si aucune année ne correspond exactement, prendre la plus récente
        if (!$academicYear) {
            $academicYear = $this->createQueryBuilder('a')
                ->orderBy('a.endDate', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
        }
        
        return $academicYear;
    }
    
    /**
     * Trouve l'année académique active
     * (une année académique peut être marquée comme active manuellement)
     */
    public function findActiveYear(): ?AcademicYear
    {
        return $this->createQueryBuilder('a')
            ->where('a.isActive = :isActive')
            ->setParameter('isActive', true)
            ->getQuery()
            ->getOneOrNullResult();
    }
}