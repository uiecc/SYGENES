<?php

namespace App\Controller;

use App\Entity\EC;
use App\Entity\Field;
use App\Entity\Level;
use App\Entity\Note;
use App\Entity\Semester;
use App\Repository\ECRepository;
use App\Repository\FieldRepository;
use App\Repository\LevelRepository;
use App\Repository\SemesterRepository;
use App\Repository\StudentRepository;
use App\Repository\NoteRepository;
use App\Repository\SchoolRepository;
use App\Repository\UERepository;
use App\Service\AcademicYearService;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/notes')]
class NoteController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private AcademicYearService $academicYearService;

    public function __construct(
        EntityManagerInterface $entityManager,
        AcademicYearService $academicYearService
    ) {
        $this->entityManager = $entityManager;
        $this->academicYearService = $academicYearService;
    }

    /**
     * Vérifie si l'utilisateur actuel est un étudiant
     */
    private function checkNotStudent(): void
    {
        if ($this->isGranted('ROLE_STUDENT')) {
            throw $this->createAccessDeniedException('Les étudiants ne sont pas autorisés à accéder à cette page.');
        }
    }

    #[Route('/', name: 'app_note_index')]
    public function index(ECRepository $ecRepository): Response
    {
        // Vérifier que l'utilisateur n'est pas un étudiant
        $this->checkNotStudent();
        
        $ecs = [];
        $user = $this->getUser();
        
        // Déterminer les ECs à afficher en fonction du rôle de l'utilisateur
        if ($this->isGranted('ROLE_SCHOOL_MANAGER')) {
            // Le gestionnaire d'école voit tous les ECs
            $ecs = $ecRepository->findAll();
        } elseif ($this->isGranted('ROLE_FIELD_MANAGER') && method_exists($user, 'getFieldManager') && $user->getFieldManager()) {
            // Le gestionnaire de filière voit les ECs de sa filière
            $field = $user->getFieldManager()->getField();
            $ecs = $ecRepository->findByField($field);
        } elseif ($this->isGranted('ROLE_LEVEL_MANAGER') && method_exists($user, 'getLevelManager') && $user->getLevelManager()) {
            // Le gestionnaire de niveau voit les ECs de son niveau
            $level = $user->getLevelManager()->getLevel();
            $ecs = $ecRepository->findByLevel($level);
        } elseif (method_exists($user, 'getTeacher') && $user->getTeacher()) {
            // Si l'utilisateur est un enseignant, montrer uniquement ses ECs
            $ecs = $ecRepository->findBy(['teacher' => $user->getTeacher()]);
        }
        
        // Organiser les ECs par semestre pour un affichage structuré
        $ecsBySemester = [];
        foreach ($ecs as $ec) {
            $semester = $ec->getUe()->getSemester();
            $semesterId = $semester->getId();
            
            if (!isset($ecsBySemester[$semesterId])) {
                $ecsBySemester[$semesterId] = [
                    'semester' => $semester,
                    'ecs' => []
                ];
            }
            
            $ecsBySemester[$semesterId]['ecs'][] = $ec;
        }

        return $this->render('note/index.html.twig', [
            'ecsBySemester' => $ecsBySemester,
        ]);
    }

    #[Route('/choose', name: 'app_note_choose')]
    public function chooseOptions(
        Request $request,
        LevelRepository $levelRepository,
        SemesterRepository $semesterRepository
    ): Response {
        // Vérifier que l'utilisateur n'est pas un étudiant
        $this->checkNotStudent();
        
        $user = $this->getUser();
        $levels = [];
        $selectedLevel = null;
        $semesters = [];
        
        // Récupérer les niveaux accessibles selon le rôle
        if ($this->isGranted('ROLE_SCHOOL_MANAGER')) {
            $levels = $levelRepository->findAll();
        } elseif ($this->isGranted('ROLE_FIELD_MANAGER') && method_exists($user, 'getFieldManager') && $user->getFieldManager()) {
            $field = $user->getFieldManager()->getField();
            $levels = $levelRepository->findByField($field);
        } elseif ($this->isGranted('ROLE_LEVEL_MANAGER') && method_exists($user, 'getLevelManager') && $user->getLevelManager()) {
            $levels = [$user->getLevelManager()->getLevel()];
        } elseif (method_exists($user, 'getTeacher') && $user->getTeacher()) {
            // Pour les enseignants, récupérer tous les niveaux où ils enseignent
            $levels = $levelRepository->findByTeacher($user->getTeacher());
        }
        
        // Récupérer les semestres si un niveau est sélectionné
        $levelId = $request->query->get('level');
        if ($levelId) {
            $selectedLevel = $levelRepository->find($levelId);
            if ($selectedLevel) {
                $semesters = $semesterRepository->findBy(['level' => $selectedLevel]);
            }
        }
        
        // Traitement du formulaire
        if ($request->isMethod('POST')) {
            $levelId = $request->request->get('level');
            $semesterId = $request->request->get('semester');
            
            if ($levelId && $semesterId) {
                return $this->redirectToRoute('app_note_fill', [
                    'level' => $levelId,
                    'semester' => $semesterId
                ]);
            }
        }
        
        return $this->render('note/choose_options.html.twig', [
            'levels' => $levels,
            'selectedLevel' => $selectedLevel,
            'semesters' => $semesters
        ]);
    }

    #[Route('/fill/{level}/{semester}/{ec}', name: 'app_note_fill')]
    public function fillNotes(
        Request $request,
        Level $level,
        Semester $semester,
        EC $ec,
        StudentRepository $studentRepository,
        NoteRepository $noteRepository
    ): Response {
        // Vérifier que l'utilisateur n'est pas un étudiant
        $this->checkNotStudent();
        
        // Vérifier que l'EC appartient bien à ce semestre
        if ($ec->getUe()->getSemester()->getId() !== $semester->getId()) {
            throw $this->createNotFoundException('Cet EC n\'appartient pas à ce semestre.');
        }
        
        // Récupérer les étudiants du niveau
        $students = $studentRepository->findBy(['level' => $level]);
        
        // Récupérer l'année académique actuelle
        $academicYear = $this->academicYearService->getCurrentAcademicYear();
        
        // Récupérer ou initialiser les notes pour cet EC
        $existingNotes = [];
        foreach ($students as $student) {
            $note = $noteRepository->findOneBy([
                'student' => $student,
                'ec' => $ec,
                'academicYear' => $academicYear
            ]);
            
            if (!$note) {
                $note = new Note();
                $note->setStudent($student);
                $note->setEc($ec);
                $note->setAcademicYear($academicYear);
            }
            
            $existingNotes[$student->getId()] = $note;
        }
        
        return $this->render('note/enter_notes.html.twig', [
            'level' => $level,
            'semester' => $semester,
            'ec' => $ec,
            'students' => $students,
            'existingNotes' => $existingNotes,
            'academicYear' => $academicYear,
        ]);
    }

    #[Route('/save/{ec}', name: 'app_note_save', methods: ['POST'])]
    public function saveNotes(
        Request $request,
        EC $ec,
        StudentRepository $studentRepository,
        NoteRepository $noteRepository
    ): Response {
        // Vérifier que l'utilisateur n'est pas un étudiant
        $this->checkNotStudent();
        
        // Récupérer les données du formulaire
        $studentIds = $request->request->all('student');
        $ccNotes = $request->request->all('cc');
        $tpNotes = $request->request->all('tp');
        $hasTP = $request->request->getBoolean('has_tp');
        
        // Récupérer l'année académique
        $academicYear = $this->academicYearService->getCurrentAcademicYear();
        
        // Mettre à jour l'EC
        $ec->setHasTP($hasTP);
        $this->entityManager->persist($ec);
        
        // Enregistrer les notes pour chaque étudiant
        foreach ($studentIds as $studentId) {
            $student = $studentRepository->find($studentId);
            if (!$student) {
                continue;
            }
            
            // Récupérer ou créer la note
            $note = $noteRepository->findOneBy([
                'student' => $student,
                'ec' => $ec,
                'academicYear' => $academicYear
            ]);
            
            if (!$note) {
                $note = new Note();
                $note->setStudent($student);
                $note->setEc($ec);
                $note->setAcademicYear($academicYear);
            }
            
            // Mettre à jour les valeurs
            // Pour le CC sur 30
            if (isset($ccNotes[$studentId]) && trim($ccNotes[$studentId]) !== '') {
                $note->setCcNote(floatval($ccNotes[$studentId]));
            }
            
            // Pour le TP sur 20
            if ($hasTP && isset($tpNotes[$studentId]) && trim($tpNotes[$studentId]) !== '') {
                $note->setTpNote(floatval($tpNotes[$studentId]));
            } else {
                $note->setTpNote(null);
            }
            
            $note->setUpdatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($note);
        }
        
        $this->entityManager->flush();
        
        $this->addFlash('success', 'Les notes ont été enregistrées avec succès.');
        
        // Rediriger vers la page de saisie des notes
        return $this->redirectToRoute('app_note_fill', [
            'level' => $ec->getUe()->getSemester()->getLevel()->getId(),
            'semester' => $ec->getUe()->getSemester()->getId(),
            'ec' => $ec->getId()
        ]);
    }

    #[Route('/pv/generate/{field}/{level}/{semester}', name: 'app_note_generate_pv')]
public function generatePV(
    Field $field,
    Level $level,
    Semester $semester = null,
    UERepository $ueRepository,
    ECRepository $ecRepository,
    StudentRepository $studentRepository,
    NoteRepository $noteRepository,
    SchoolRepository $schoolRepository
): Response {
    // Vérifier que l'utilisateur n'est pas un étudiant
    $this->checkNotStudent();
    
    // Vérifier que le niveau appartient bien à la filière
    if ($level->getField()->getId() !== $field->getId()) {
        throw $this->createNotFoundException('Ce niveau n\'appartient pas à cette filière.');
    }
    
    // Si aucun semestre n'est spécifié, utiliser le premier semestre du niveau
    if (!$semester) {
        $semester = $level->getSemesters()->first();
        
        if (!$semester) {
            throw $this->createNotFoundException('Aucun semestre trouvé pour ce niveau.');
        }
    } else {
        // Vérifier que le semestre appartient bien au niveau
        if ($semester->getLevel()->getId() !== $level->getId()) {
            throw $this->createNotFoundException('Ce semestre n\'appartient pas à ce niveau.');
        }
    }
    
    // Récupérer les UEs du semestre
    $ues = $ueRepository->findBy(['semester' => $semester]);
    
    // Récupérer les étudiants du niveau
    $students = $studentRepository->findBy(['level' => $level], ['lastName' => 'ASC', 'firstName' => 'ASC']);
    
    // Récupérer l'année académique actuelle
    $academicYear = $this->academicYearService->getCurrentAcademicYear();
    
    // Récupérer l'école
    $school = $schoolRepository->findOneBy([]);
    
    // Préparer les données pour le PV
    $data = [];
    
    foreach ($ues as $ue) {
        $ueData = [
            'ue' => $ue,
            'ecs' => [],
        ];
        
        // Récupérer les ECs de cette UE
        $ecs = $ecRepository->findBy(['ue' => $ue]);
        
        foreach ($ecs as $ec) {
            $ecData = [
                'ec' => $ec,
                'notes' => [],
            ];
            
            // Récupérer les notes pour chaque étudiant
            foreach ($students as $student) {
                $note = $noteRepository->findOneBy([
                    'student' => $student,
                    'ec' => $ec,
                    'academicYear' => $academicYear
                ]);
                
                // Stocker la note par ID d'étudiant pour un accès facile dans le template
                $ecData['notes'][$student->getId()] = $note;
            }
            
            $ueData['ecs'][] = $ecData;
        }
        
        $data[] = $ueData;
    }
    
    // Générer le PDF
    $html = $this->renderView('note/pv.html.twig', [
        'field' => $field,
        'level' => $level,
        'semester' => $semester,
        'academicYear' => $academicYear,
        'students' => $students,
        'data' => $data,
        'school' => $school,
        'date' => new \DateTime(),
    ]);
    
    // Configuration Dompdf
    $options = new Options();
    $options->set('defaultFont', 'Arial');
    $options->set('isRemoteEnabled', true);
    
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    
    // Nom du fichier
    $filename = sprintf('PV_%s_%s_%s.pdf', 
        $field->getCode(), 
        $level->getCode(), 
        $semester->getCode()
    );
    
    // Envoi du PDF au navigateur
    return new Response(
        $dompdf->output(),
        Response::HTTP_OK,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]
    );
}

#[Route('/pv', name: 'app_note_pv_index')]
public function pvIndex(
    FieldRepository $fieldRepository,
    LevelRepository $levelRepository,
    SemesterRepository $semesterRepository
): Response {
    // Vérifier que l'utilisateur n'est pas un étudiant
    $this->checkNotStudent();
    
    $user = $this->getUser();
    $fields = [];
    
    // Déterminer les filières accessibles selon le rôle
    if ($this->isGranted('ROLE_SCHOOL_MANAGER')) {
        // Le gestionnaire d'école voit toutes les filières
        $fields = $fieldRepository->findAll();
    } elseif ($this->isGranted('ROLE_FIELD_MANAGER') && method_exists($user, 'getFieldManager') && $user->getFieldManager()) {
        // Le gestionnaire de filière voit sa filière
        $fields = [$user->getFieldManager()->getField()];
    } elseif ($this->isGranted('ROLE_LEVEL_MANAGER') && method_exists($user, 'getLevelManager') && $user->getLevelManager()) {
        // Le gestionnaire de niveau voit la filière de son niveau
        $level = $user->getLevelManager()->getLevel();
        $fields = [$level->getField()];
    }
    
    // Préparer les données structurées
    $data = [];
    
    foreach ($fields as $field) {
        $fieldData = [
            'field' => $field,
            'levels' => []
        ];
        
        // Récupérer les niveaux de cette filière
        $levels = $levelRepository->findBy(['field' => $field]);
        
        foreach ($levels as $level) {
            $levelData = [
                'level' => $level,
                'semesters' => []
            ];
            
            // Récupérer les semestres de ce niveau
            $semesters = $semesterRepository->findBy(['level' => $level]);
            $levelData['semesters'] = $semesters;
            
            $fieldData['levels'][] = $levelData;
        }
        
        $data[] = $fieldData;
    }
    
    return $this->render('note/pv_index.html.twig', [
        'data' => $data
    ]);
}
}