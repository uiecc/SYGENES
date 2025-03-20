<?php

namespace App\Entity;

use App\Repository\ExamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExamRepository::class)]
class Exam
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'exams')]
    private ?EC $ec = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $examDate = null;

    #[ORM\Column(nullable: true)]
    private ?float $weight = null;

    #[ORM\ManyToOne(inversedBy: 'exams')]
    private ?AcademicYear $academicYear = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'exams')]
    private ?self $originalExam = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'originalExam')]
    private Collection $exams;

    /**
     * @var Collection<int, AnonymousCode>
     */
    #[ORM\OneToMany(targetEntity: AnonymousCode::class, mappedBy: 'exam')]
    private Collection $anonymousCodes;

    public function __construct()
    {
        $this->exams = new ArrayCollection();
        $this->anonymousCodes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getExamDate(): ?\DateTimeInterface
    {
        return $this->examDate;
    }

    public function setExamDate(\DateTimeInterface $examDate): static
    {
        $this->examDate = $examDate;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getAcademicYear(): ?AcademicYear
    {
        return $this->academicYear;
    }

    public function setAcademicYear(?AcademicYear $academicYear): static
    {
        $this->academicYear = $academicYear;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getOriginalExam(): ?self
    {
        return $this->originalExam;
    }

    public function setOriginalExam(?self $originalExam): static
    {
        $this->originalExam = $originalExam;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getExams(): Collection
    {
        return $this->exams;
    }

    public function addExam(self $exam): static
    {
        if (!$this->exams->contains($exam)) {
            $this->exams->add($exam);
            $exam->setOriginalExam($this);
        }

        return $this;
    }

    public function removeExam(self $exam): static
    {
        if ($this->exams->removeElement($exam)) {
            // set the owning side to null (unless already changed)
            if ($exam->getOriginalExam() === $this) {
                $exam->setOriginalExam(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AnonymousCode>
     */
    public function getAnonymousCodes(): Collection
    {
        return $this->anonymousCodes;
    }

    public function addAnonymousCode(AnonymousCode $anonymousCode): static
    {
        if (!$this->anonymousCodes->contains($anonymousCode)) {
            $this->anonymousCodes->add($anonymousCode);
            $anonymousCode->setExam($this);
        }

        return $this;
    }

    public function removeAnonymousCode(AnonymousCode $anonymousCode): static
    {
        if ($this->anonymousCodes->removeElement($anonymousCode)) {
            // set the owning side to null (unless already changed)
            if ($anonymousCode->getExam() === $this) {
                $anonymousCode->setExam(null);
            }
        }

        return $this;
    }
}
