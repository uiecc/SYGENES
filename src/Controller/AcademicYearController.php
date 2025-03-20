<?php

namespace App\Controller;

use App\Entity\AcademicYear;
use App\Form\AcademicYearType;
use App\Repository\AcademicYearRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/academic-year')]
#[IsGranted('ROLE_SCHOOL_MANAGER')]
class AcademicYearController extends AbstractController
{
    #[Route('/', name: 'app_academic_year_index')]
    public function index(AcademicYearRepository $academicYearRepository): Response
    {
        $academicYears = $academicYearRepository->findBy([], ['startDate' => 'DESC']);
        
        return $this->render('academic_year/index.html.twig', [
            'academic_years' => $academicYears,
        ]);
    }
    
    #[Route('/new', name: 'app_academic_year_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $academicYear = new AcademicYear();
        $form = $this->createForm(AcademicYearType::class, $academicYear);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Si cette année est définie comme courante, désactiver les autres
            if ($academicYear->IsCurrent()) {
                $this->resetCurrentAcademicYear($entityManager);
            }
            
            $entityManager->persist($academicYear);
            $entityManager->flush();
            
            $this->addFlash('success', 'Année académique créée avec succès.');
            return $this->redirectToRoute('app_academic_year_index');
        }
        
        return $this->render('academic_year/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/{id}/edit', name: 'app_academic_year_edit')]
    public function edit(Request $request, AcademicYear $academicYear, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AcademicYearType::class, $academicYear);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // Si cette année est définie comme courante, désactiver les autres
            if ($academicYear->isIsCurrent()) {
                $this->resetCurrentAcademicYear($entityManager, $academicYear->getId());
            }
            
            $entityManager->flush();
            
            $this->addFlash('success', 'Année académique modifiée avec succès.');
            return $this->redirectToRoute('app_academic_year_index');
        }
        
        return $this->render('academic_year/edit.html.twig', [
            'academic_year' => $academicYear,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/{id}/delete', name: 'app_academic_year_delete', methods: ['POST'])]
    public function delete(Request $request, AcademicYear $academicYear, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$academicYear->getId(), $request->request->get('_token'))) {
            $entityManager->remove($academicYear);
            $entityManager->flush();
            $this->addFlash('success', 'Année académique supprimée avec succès.');
        }
        
        return $this->redirectToRoute('app_academic_year_index');
    }
    
    #[Route('/{id}/set-current', name: 'app_academic_year_set_current')]
    public function setCurrent(AcademicYear $academicYear, EntityManagerInterface $entityManager): Response
    {
        // Désactiver l'année académique courante
        $this->resetCurrentAcademicYear($entityManager);
        
        // Définir la nouvelle année comme courante
        $academicYear->setIsCurrent(true);
        $entityManager->flush();
        
        $this->addFlash('success', sprintf('L\'année académique %s est maintenant définie comme l\'année en cours.', $academicYear->getName()));
        
        return $this->redirectToRoute('app_academic_year_index');
    }
    
    /**
     * Réinitialise l'année académique courante en mettant isCurrent à false pour toutes les années,
     * sauf celle ayant l'ID spécifié
     */
    private function resetCurrentAcademicYear(EntityManagerInterface $entityManager, ?int $exceptId = null): void
    {
        $queryBuilder = $entityManager->createQueryBuilder()
            ->update(AcademicYear::class, 'a')
            ->set('a.isCurrent', 'false')
            ->where('a.isCurrent = true');
        
        if ($exceptId !== null) {
            $queryBuilder->andWhere('a.id != :id')
                ->setParameter('id', $exceptId);
        }
        
        $queryBuilder->getQuery()->execute();
    }
}