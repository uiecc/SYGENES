<?php

namespace App\Entity;

use App\Repository\FieldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FieldRepository::class)]
class Field
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\ManyToOne(inversedBy: 'fields')]
    #[ORM\JoinColumn(nullable: false)]
    private ?School $school = null;

    /**
     * @var Collection<int, Level>
     */
    #[ORM\OneToMany(targetEntity: Level::class, mappedBy: 'field')]
    private Collection $levels;

    #[ORM\OneToOne(mappedBy: 'field', targetEntity: FieldManager::class)]
    private ?FieldManager $fieldManager = null;

    public function __construct()
    {
        $this->levels = new ArrayCollection();
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

    public function getSchool(): ?School
    {
        return $this->school;
    }

    public function setSchool(?School $school): static
    {
        $this->school = $school;

        return $this;
    }

    /**
     * @return Collection<int, Level>
     */
    public function getLevels(): Collection
    {
        return $this->levels;
    }

    public function addLevel(Level $level): static
    {
        if (!$this->levels->contains($level)) {
            $this->levels->add($level);
            $level->setField($this);
        }

        return $this;
    }

    public function removeLevel(Level $level): static
    {
        if ($this->levels->removeElement($level)) {
            // set the owning side to null (unless already changed)
            if ($level->getField() === $this) {
                $level->setField(null);
            }
        }

        return $this;
    }

    public function getFieldManager(): ?FieldManager
    {
        return $this->fieldManager;
    }

    public function setFieldManager(?FieldManager $fieldManager): static
    {
        // unset the owning side of the relation if necessary
        if ($fieldManager === null && $this->fieldManager !== null) {
            $this->fieldManager->setField(null);
        }

        // set the owning side of the relation if necessary
        if ($fieldManager !== null && $fieldManager->getField() !== $this) {
            $fieldManager->setField($this);
        }

        $this->fieldManager = $fieldManager;

        return $this;
    }
}
