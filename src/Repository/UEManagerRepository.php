<?php

namespace App\Repository;

use App\Entity\UEManager;
use App\Entity\Level;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UEManager>
 */
class UEManagerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UEManager::class);
    }

    /**
     * Trouve tous les responsables d'UE associés aux UE d'un niveau spécifique
     * 
     * @param Level $level
     * @return UEManager[]
     */
    public function findByLevelUEs(Level $level): array
    {
        return $this->createQueryBuilder('um')
            ->join('um.ue', 'u') // Notez le changement ici: ue au singulier
            ->join('u.semester', 's')
            ->where('s.level = :level')
            ->setParameter('level', $level)
            ->orderBy('um.lastName', 'ASC')
            ->getQuery()
            ->getResult();
    }
}