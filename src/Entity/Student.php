<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student extends User
{
    #[ORM\Column(length: 1, nullable: true)]
    private ?string $sex = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateOfBirth = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $placeOfBirth = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $matricule = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nationality = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $parentContact = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $address = null;

    #[ORM\ManyToOne(inversedBy: 'students')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Level $level = null;

    /**
     * @var Collection<int, StudentUE>
     */
    #[ORM\OneToMany(mappedBy: 'student', targetEntity: StudentUE::class, orphanRemoval: true)]
    private Collection $studentUEs;

    public function __construct()
    {
        parent::__construct();
        $this->studentUEs = new ArrayCollection();
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(?string $sex): static
    {
        $this->sex = $sex;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?\DateTimeInterface $dateOfBirth): static
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getPlaceOfBirth(): ?string
    {
        return $this->placeOfBirth;
    }

    public function setPlaceOfBirth(?string $placeOfBirth): static
    {
        $this->placeOfBirth = $placeOfBirth;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(?string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): static
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getParentContact(): ?string
    {
        return $this->parentContact;
    }

    public function setParentContact(?string $parentContact): static
    {
        $this->parentContact = $parentContact;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getLevel(): ?Level
    {
        return $this->level;
    }

    public function setLevel(?Level $level): static
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return Collection<int, StudentUE>
     */
    public function getStudentUEs(): Collection
    {
        return $this->studentUEs;
    }

    public function addStudentUE(StudentUE $studentUE): static
    {
        if (!$this->studentUEs->contains($studentUE)) {
            $this->studentUEs->add($studentUE);
            $studentUE->setStudent($this);
        }

        return $this;
    }

    public function removeStudentUE(StudentUE $studentUE): static
    {
        if ($this->studentUEs->removeElement($studentUE)) {
            // set the owning side to null (unless already changed)
            if ($studentUE->getStudent() === $this) {
                $studentUE->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * Vérifie si l'étudiant est inscrit à une UE spécifique pour l'année académique en cours
     */
    public function isRegisteredToUE(UE $ue, string $academicYear): bool
    {
        foreach ($this->studentUEs as $studentUE) {
            if ($studentUE->getUe() === $ue && $studentUE->getAcademicYear() === $academicYear) {
                return true;
            }
        }
        
        return false;
    }
    
}