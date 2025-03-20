<?php

namespace App\Entity;

use App\Repository\AnonymousCodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnonymousCodeRepository::class)]
class AnonymousCode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\ManyToOne(inversedBy: 'anonymousCodes')]
    private ?Student $student = null;

    #[ORM\ManyToOne(inversedBy: 'anonymousCodes')]
    private ?Exam $exam = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, ExamGrade>
     */
    #[ORM\OneToMany(targetEntity: ExamGrade::class, mappedBy: 'anonymousCode')]
    private Collection $examGrades;

    public function __construct()
    {
        $this->examGrades = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
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

    public function getExam(): ?Exam
    {
        return $this->exam;
    }

    public function setExam(?Exam $exam): static
    {
        $this->exam = $exam;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createAt): static
    {
        $this->createdAt = $createAt;

        return $this;
    }

    /**
     * @return Collection<int, ExamGrade>
     */
    public function getExamGrades(): Collection
    {
        return $this->examGrades;
    }

    public function addExamGrade(ExamGrade $examGrade): static
    {
        if (!$this->examGrades->contains($examGrade)) {
            $this->examGrades->add($examGrade);
            $examGrade->setAnonymousCode($this);
        }

        return $this;
    }

    public function removeExamGrade(ExamGrade $examGrade): static
    {
        if ($this->examGrades->removeElement($examGrade)) {
            // set the owning side to null (unless already changed)
            if ($examGrade->getAnonymousCode() === $this) {
                $examGrade->setAnonymousCode(null);
            }
        }

        return $this;
    }
}
