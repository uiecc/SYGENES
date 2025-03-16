<?php

namespace App\Controller;

use App\Entity\LevelManager;
use App\Repository\UERepository;
use App\Repository\ECRepository;
use App\Repository\SemesterRepository;
use App\Repository\UEManagerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/levelmanager')]
#[IsGranted('ROLE_LEVEL_MANAGER')]
class LevelManagerDashboardController extends AbstractController
{
    #[Route('', name: 'level_manager_dashboard')]
    public function dashboard(
        UERepository $ueRepository,
        ECRepository $ecRepository,
        SemesterRepository $semesterRepository,
        UEManagerRepository $ueManagerRepository
    ): Response {
        /** @var LevelManager $levelManager */
        $levelManager = $this->getUser();
        $level = $levelManager->getLevel();
        
        // Si le level n'est pas défini, rediriger vers une page d'erreur
        if (!$level) {
            $this->addFlash('error', 'Aucun niveau n\'est associé à votre compte.');
            return $this->redirectToRoute('app_error');
        }
        
        // Récupérer les statistiques
        $stats = [
            'total_ues' => $ueRepository->countByLevel($level),
            'compulsory_ues' => $ueRepository->countCompulsoryByLevel($level),
            'optional_ues' => $ueRepository->countOptionalByLevel($level),
            'total_ecs' => $ecRepository->countByLevel($level),
        ];
        
        // Récupérer les données pour les graphiques et tableaux
        $uesBySemester = $ueRepository->countBySemester($level);
        $ecsBySemester = $ecRepository->countBySemester($level);
        
        // Récupérer toutes les UE par semestre
        $uesByLevel = $ueRepository->findByLevelOrderedBySemester($level);
        
        // Récupérer tous les responsables d'UE associés à ce niveau
        $ueManagers = $ueManagerRepository->findByLevelUEs($level);
        
        return $this->render('level_manager_dashboard/dashboard.html.twig', [
            'levelManager' => $levelManager,
            'level' => $level,
            'stats' => $stats,
            'uesBySemester' => $uesBySemester,
            'ecsBySemester' => $ecsBySemester,
            'uesByLevel' => $uesByLevel,
            'ueManagers' => $ueManagers,
        ]);
    }
}