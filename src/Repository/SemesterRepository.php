<?php

namespace App\Repository;

use App\Entity\Semester;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Semester>
 */
class SemesterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Semester::class);
    }

    /**
     * Trouve le semestre actuel basé sur la date courante
     * 
     * @return Semester|null Le semestre actuel ou null si aucun n'est trouvé
     */
    public function findCurrentSemester(): ?Semester
    {
        // Déterminer le semestre actuel en fonction de la date
        $currentMonth = (int)date('m');
        
        // Si nous sommes entre janvier et juin (mois 1-6), c'est le semestre 2
        // Sinon (mois 7-12), c'est le semestre 1
        $semesterCode = ($currentMonth >= 1 && $currentMonth <= 6) ? 'S2' : 'S1';
        
        // Récupérer le dernier semestre correspondant au code
        return $this->createQueryBuilder('s')
            ->where('s.code LIKE :code')
            ->setParameter('code', $semesterCode . '%')
            ->orderBy('s.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    /**
     * Trouve un semestre pour un niveau et un code spécifiques
     */
    public function findOneByLevelAndCode($level, $code): ?Semester
    {
        return $this->createQueryBuilder('s')
            ->where('s.level = :level')
            ->andWhere('s.code = :code')
            ->setParameter('level', $level)
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult();
    }
    
    /**
     * Trouve tous les semestres pour un niveau spécifique
     */
    public function findByLevel($level)
    {
        return $this->createQueryBuilder('s')
            ->where('s.level = :level')
            ->setParameter('level', $level)
            ->orderBy('s.code', 'ASC')
            ->getQuery()
            ->getResult();
    }
}