<?php

namespace App\Entity;

use App\Repository\SemesterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SemesterRepository::class)]
class Semester
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\ManyToOne(inversedBy: 'semesters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Level $level = null;

    /**
     * @var Collection<int, UE>
     */
    #[ORM\OneToMany(targetEntity: UE::class, mappedBy: 'semester')]
    private Collection $uEs;

    public function __construct()
    {
        $this->uEs = new ArrayCollection();
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
     * @return Collection<int, UE>
     */
    public function getUEs(): Collection
    {
        return $this->uEs;
    }

    public function addUE(UE $uE): static
    {
        if (!$this->uEs->contains($uE)) {
            $this->uEs->add($uE);
            $uE->setSemester($this);
        }

        return $this;
    }

    public function removeUE(UE $uE): static
    {
        if ($this->uEs->removeElement($uE)) {
            // set the owning side to null (unless already changed)
            if ($uE->getSemester() === $this) {
                $uE->setSemester(null);
            }
        }

        return $this;
    }
}
