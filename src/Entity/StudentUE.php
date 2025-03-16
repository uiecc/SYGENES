<?php

namespace App\Entity;

use App\Repository\StudentUERepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentUERepository::class)]
class StudentUE
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'studentUEs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Student $student = null;

    #[ORM\ManyToOne(inversedBy: 'studentUEs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UE $ue = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $registeredAt = null;

    #[ORM\Column(length: 20)]
    private ?string $academicYear = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $status = 'registered';
    
    #[ORM\Column(nullable: true)]
    private ?float $grade = null;
    
    #[ORM\Column(nullable: true)]
    private ?bool $isValidated = null;

    public function __construct()
    {
        $this->registeredAt = new \DateTimeImmutable();
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

    public function getUe(): ?UE
    {
        return $this->ue;
    }

    public function setUe(?UE $ue): static
    {
        $this->ue = $ue;

        return $this;
    }

    public function getRegisteredAt(): ?\DateTimeImmutable
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(\DateTimeImmutable $registeredAt): static
    {
        $this->registeredAt = $registeredAt;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }
    
    public function getGrade(): ?float
    {
        return $this->grade;
    }
    
    public function setGrade(?float $grade): static
    {
        $this->grade = $grade;
        
        return $this;
    }
    
    public function isValidated(): ?bool
    {
        return $this->isValidated;
    }
    
    public function setIsValidated(?bool $isValidated): static
    {
        $this->isValidated = $isValidated;
        
        return $this;
    }
}