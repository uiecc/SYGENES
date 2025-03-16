<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Student $student = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?EC $ec = null;

    #[ORM\Column(nullable: true)]
    private ?float $ccNote = null; // Note de CC sur 30

    #[ORM\Column(nullable: true)]
    private ?float $tpNote = null; // Note de TP sur 20

    #[ORM\Column(length: 255)]
    private ?string $academicYear = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): static
    {
        $this->student = $student;

        return $this;
    }

    public function getEc(): ?EC
    {
        return $this->ec;
    }

    public function setEc(?EC $ec): static
    {
        $this->ec = $ec;

        return $this;
    }

    public function getCcNote(): ?float
    {
        return $this->ccNote;
    }

    public function setCcNote(?float $ccNote): static
    {
        $this->ccNote = $ccNote;

        return $this;
    }

    public function getTpNote(): ?float
    {
        return $this->tpNote;
    }

    public function setTpNote(?float $tpNote): static
    {
        $this->tpNote = $tpNote;

        return $this;
    }

    public function getAcademicYear(): ?string
    {
        return $this->academicYear;
    }

    public function setAcademicYear(string $academicYear): static
    {
        $this->academicYear = $academicYear;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Calcule la note totale (CC + TP si disponible)
     * 
     * @return float|null La note totale ou null si aucune note n'est définie
     */
    public function getTotalNote(): ?float
    {
        $total = 0;
        $hasNotes = false;

        if ($this->ccNote !== null) {
            $total += $this->ccNote;
            $hasNotes = true;
        }

        if ($this->tpNote !== null) {
            $total += $this->tpNote;
            $hasNotes = true;
        }

        return $hasNotes ? $total : null;
    }

    /**
     * Vérifie si cette note a un TP
     * 
     * @return bool true si la note de TP est définie
     */
    public function hasTP(): bool
    {
        return $this->tpNote !== null;
    }
}