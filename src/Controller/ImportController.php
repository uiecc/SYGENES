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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Persistence\ManagerRegistry;


class ImportController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;
    private ManagerRegistry $doctrine;

    public function __construct(
        EntityManagerInterface $entityManager, 
        LoggerInterface $logger,
        ManagerRegistry $doctrine
    ) {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->doctrine = $doctrine;
    }
    #[Route('/import', name: 'import_students')]
    public function import(Request $request, ExcelImportService $excelImportService): Response
    {
        /** @var UploadedFile|null $file */
        $file = $request->files->get('students_file');
        $user = $this->getUser(); // Récupérer l'utilisateur connecté

        if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour importer des étudiants.');
            return $this->render('import/import.html.twig');
        }

        $fieldManager = $this->entityManager->getRepository(FieldManager::class)->find($user->getId());

        if (!$fieldManager) {
            $this->addFlash('error', 'FieldManager non trouvé. Vérifiez vos permissions.');
            return $this->render('import/import.html.twig');
        }

        if ($file) {
            try {
                // Vérification du type de fichier
                $mimeType = $file->getMimeType();
                $allowedTypes = [
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ];

                if (!in_array($mimeType, $allowedTypes)) {
                    $this->addFlash('error', 'Le format du fichier n\'est pas valide. Utilisez .xls ou .xlsx');
                    return $this->render('import/import.html.twig');
                }

                // Vérification de la taille du fichier (10MB max)
                if ($file->getSize() > 10 * 1024 * 1024) {
                    $this->addFlash('error', 'La taille du fichier ne doit pas dépasser 10MB.');
                    return $this->render('import/import.html.twig');
                }

                $filePath = $file->getPathname();
                $result = $excelImportService->importStudents($filePath, $fieldManager);

                $failedStudents = $result['failed'] ?? [];
                $successCount = $result['success'] ?? 0;

                if ($successCount > 0) {
                    $this->addFlash('success', $successCount . ' étudiant(s) ont été importés avec succès!');
                }

                if (!empty($failedStudents)) {
                    $message = 'Les étudiants suivants n\'ont pas pu être importés : ';
                    foreach ($failedStudents as $failed) {
                        $student = $failed['student'];
                        $reason = $failed['reason'];
                        $message .= sprintf('%s (%s), ', $student->getLastName(), $reason);
                    }
                    $this->addFlash('warning', rtrim($message, ', '));
                }
            } catch (\Exception $e) {
                $this->logger->error('Erreur lors de l\'import des étudiants: ' . $e->getMessage());
                $this->addFlash('error', 'Une erreur est survenue lors de l\'import: ' . $e->getMessage());
            }
        }

        return $this->render('import/import.html.twig');
    }


}
