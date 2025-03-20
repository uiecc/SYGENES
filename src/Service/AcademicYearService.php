<?php

namespace App\Service;

use App\Entity\AcademicYear;
use App\Repository\AcademicYearRepository;
use Doctrine\ORM\EntityManagerInterface;

class AcademicYearService
{
    private AcademicYearRepository $academicYearRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        AcademicYearRepository $academicYearRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->academicYearRepository = $academicYearRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Récupère l'année académique actuelle
     * Si aucune année n'est marquée comme courante, prend l'année qui contient la date actuelle
     * Si aucune année ne contient la date actuelle, prend la plus récente
     */
    public function getCurrentAcademicYear(): ?AcademicYear
    {
        // Essayer de trouver l'année académique marquée comme courante
        $currentAcademicYear = $this->academicYearRepository->findOneBy(['isCurrent' => true]);
        
        if ($currentAcademicYear) {
            return $currentAcademicYear;
        }
        
        // Sinon, essayer de trouver l'année qui contient la date actuelle
        $today = new \DateTime();
        $academicYear = $this->academicYearRepository->createQueryBuilder('a')
            ->where('a.startDate <= :today')
            ->andWhere('a.endDate >= :today')
            ->setParameter('today', $today)
            ->getQuery()
            ->getOneOrNullResult();
        
        if ($academicYear) {
            return $academicYear;
        }
        
        // Sinon, prendre la plus récente
        $latestAcademicYear = $this->academicYearRepository->findOneBy([], ['endDate' => 'DESC']);
        
        return $latestAcademicYear;
    }
    
    /**
     * Définit une année académique comme l'année en cours
     */
    public function setCurrentAcademicYear(AcademicYear $academicYear): void
    {
        // Réinitialiser toutes les années académiques
        $this->entityManager->createQueryBuilder()
            ->update(AcademicYear::class, 'a')
            ->set('a.isCurrent', 'false')
            ->getQuery()
            ->execute();
        
        // Définir la nouvelle année comme courante
        $academicYear->setIsCurrent(true);
        $this->entityManager->flush();
    }
    
    /**
     * Récupère toutes les années académiques triées par date de début décroissante
     */
    public function getAllAcademicYears(): array
    {
        return $this->academicYearRepository->findBy([], ['startDate' => 'DESC']);
    }
}