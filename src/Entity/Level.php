<?php

namespace App\Entity;

use App\Repository\LevelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LevelRepository::class)]
class Level
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\ManyToOne(inversedBy: 'levels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Field $filed = null;

    /**
     * @var Collection<int, Semester>
     */
    #[ORM\OneToMany(targetEntity: Semester::class, mappedBy: 'level')]
    private Collection $semesters;

    public function __construct()
    {
        $this->semesters = new ArrayCollection();
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

    public function getFiled(): ?Field
    {
        return $this->filed;
    }

    public function setFiled(?Field $filed): static
    {
        $this->filed = $filed;

        return $this;
    }

    /**
     * @return Collection<int, Semester>
     */
    public function getSemesters(): Collection
    {
        return $this->semesters;
    }

    public function addSemester(Semester $semester): static
    {
        if (!$this->semesters->contains($semester)) {
            $this->semesters->add($semester);
            $semester->setLevel($this);
        }

        return $this;
    }

    public function removeSemester(Semester $semester): static
    {
        if ($this->semesters->removeElement($semester)) {
            // set the owning side to null (unless already changed)
            if ($semester->getLevel() === $this) {
                $semester->setLevel(null);
            }
        }

        return $this;
    }
}
