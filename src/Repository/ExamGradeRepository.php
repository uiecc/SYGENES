<?php

namespace App\Repository;

use App\Entity\AcademicYear;
use App\Entity\Exam;
use App\Entity\ExamGrade;
use App\Entity\Level;
use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExamGrade>
 */
class ExamGradeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExamGrade::class);
    }

    /**
     * @return ExamGrade[] Returns an array of ExamGrade objects
     */
    public function findResultsByExam($exam): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.anonymousCode', 'a')
            ->andWhere('a.exam = :exam')
            ->setParameter('exam', $exam)
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    /**
     * Trouve tous les résultats d'examen avec les informations complètes des étudiants
     * 
     * @param Exam $exam L'examen pour lequel on recherche les résultats
     * @return array<int, array> Les résultats d'examen avec les détails des étudiants
     */
    public function findCompleteResultsByExam(Exam $exam): array
    {
        return $this->createQueryBuilder('eg')
            ->select('eg', 'ac', 's')
            ->join('eg.anonymousCode', 'ac')
            ->join('ac.student', 's')
            ->where('ac.exam = :exam')
            ->setParameter('exam', $exam)
            ->orderBy('s.lastName', 'ASC')
            ->addOrderBy('s.firstName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve la note d'un étudiant pour un examen spécifique
     */
    public function findOneByExamAndStudent(Exam $exam, Student $student): ?ExamGrade
    {
        return $this->createQueryBuilder('eg')
            ->join('eg.anonymousCode', 'ac')
            ->where('ac.exam = :exam')
            ->andWhere('ac.student = :student')
            ->setParameter('exam', $exam)
            ->setParameter('student', $student)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Calcule les statistiques pour un examen
     */
    public function getExamStatistics(Exam $exam): array
    {
        $results = $this->createQueryBuilder('eg')
            ->select('COUNT(eg.id) as total', 
                     'MIN(eg.grade) as min', 
                     'MAX(eg.grade) as max', 
                     'AVG(eg.grade) as average',
                     'SUM(CASE WHEN eg.grade >= 10 THEN 1 ELSE 0 END) as passed')
            ->join('eg.anonymousCode', 'ac')
            ->where('ac.exam = :exam')
            ->setParameter('exam', $exam)
            ->getQuery()
            ->getOneOrNullResult();
        
        // Calculer le taux de réussite si des notes existent
        if ($results['total'] > 0) {
            $results['successRate'] = ($results['passed'] / $results['total']) * 100;
        } else {
            $results['successRate'] = 0;
        }
        
        return $results;
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
    
        //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
