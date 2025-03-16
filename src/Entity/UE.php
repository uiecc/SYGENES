<?php

namespace App\Entity;

use App\Repository\UERepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UERepository::class)]
class UE
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column]
    private ?int $credit = null;

    #[ORM\Column(options: ["default" => true])]
    private bool $isCompulsory = true;

    #[ORM\ManyToOne(inversedBy: 'uEs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Semester $semester = null;

    /**
     * @var Collection<int, EC>
     */
    #[ORM\OneToMany(targetEntity: EC::class, mappedBy: 'ue')]
    private Collection $eCs;

    #[ORM\OneToOne(mappedBy: 'ue', cascade: ['persist', 'remove'])]
    private ?UEManager $uEManager = null;

    /**
     * @var Collection<int, StudentUE>
     */
    #[ORM\OneToMany(mappedBy: 'ue', targetEntity: StudentUE::class, orphanRemoval: true)]
    private Collection $studentUEs;

    public function __construct()
    {
        $this->eCs = new ArrayCollection();
        $this->studentUEs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
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

    public function getCredit(): ?int
    {
        return $this->credit;
    }

    public function setCredit(int $credit): static
    {
        $this->credit = $credit;

        return $this;
    }

    public function isCompulsory(): bool
    {
        return $this->isCompulsory;
    }

    public function setIsCompulsory(bool $isCompulsory): static
    {
        $this->isCompulsory = $isCompulsory;

        return $this;
    }

    public function getSemester(): ?Semester
    {
        return $this->semester;
    }

    public function setSemester(?Semester $semester): static
    {
        $this->semester = $semester;

        return $this;
    }

    /**
     * @return Collection<int, EC>
     */
    public function getECs(): Collection
    {
        return $this->eCs;
    }

    public function addEC(EC $eC): static
    {
        if (!$this->eCs->contains($eC)) {
            $this->eCs->add($eC);
            $eC->setUe($this);
        }

        return $this;
    }

    public function removeEC(EC $eC): static
    {
        if ($this->eCs->removeElement($eC)) {
            // set the owning side to null (unless already changed)
            if ($eC->getUe() === $this) {
                $eC->setUe(null);
            }
        }

        return $this;
    }

    public function getUEManager(): ?UEManager
    {
        return $this->uEManager;
    }

    public function setUEManager(?UEManager $uEManager): static
    {
        // unset the owning side of the relation if necessary
        if ($uEManager === null && $this->uEManager !== null) {
            $this->uEManager->setUe(null);
        }

        // set the owning side of the relation if necessary
        if ($uEManager !== null && $uEManager->getUe() !== $this) {
            $uEManager->setUe($this);
        }

        $this->uEManager = $uEManager;

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
            $studentUE->setUe($this);
        }

        return $this;
    }

    public function removeStudentUE(StudentUE $studentUE): static
    {
        if ($this->studentUEs->removeElement($studentUE)) {
            // set the owning side to null (unless already changed)
            if ($studentUE->getUe() === $this) {
                $studentUE->setUe(null);
            }
        }

        return $this;
    }
}