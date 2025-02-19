<?php

namespace App\Controller;

use App\Entity\FieldManager;
use App\Service\ExcelImportService;
use App\Repository\FieldManagerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImportController extends AbstractController
{
    private $entityManager;
    private $logger;
    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    // Dans le contrôleur
    #[Route('/import', name: 'import_students')]
    public function import(Request $request, ExcelImportService $excelImportService): Response
    {
        $file = $request->files->get('students_file');
        $user = $this->getUser(); // Récupérer l'utilisateur connecté
        $fieldManager = $this->entityManager->getRepository(FieldManager::class)->find($user->getId());

        if (!$fieldManager) {
            throw $this->createNotFoundException('FieldManager non trouvé.');
        }

        if ($file) {
            $filePath = $file->getPathname();
            $failedStudents = $excelImportService->importStudents($filePath, $fieldManager);

            $this->addFlash('success', 'Les étudiants ont été importés avec succès!');

            if (!empty($failedStudents)) {
                $message = 'Les étudiants suivants n\'ont pas pu être importés : ';
                foreach ($failedStudents as $failed) {
                    $student = $failed['student'];
                    $reason = $failed['reason'];
                    $message .= sprintf('%s (%s), ', $student->getLastName(), $reason);
                }
                $this->addFlash('warning', rtrim($message, ', '));
            }
        }

        return $this->render('import/import.html.twig');
    }
}
