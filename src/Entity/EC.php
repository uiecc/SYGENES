<?php

namespace App\Entity;

use App\Repository\ECRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ECRepository::class)]
class EC
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;


    #[ORM\ManyToOne(inversedBy: 'eCs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UE $ue = null;

    #[ORM\Column]
    private ?int $credit = null;

    #[ORM\OneToOne(mappedBy: 'ec', cascade: ['persist', 'remove'])]
    private ?Teacher $teacher = null;

    #[ORM\Column]
    private bool $hasTP = false;

    /**
     * @var Collection<int, Note>
     */
    #[ORM\OneToMany(targetEntity: Note::class, mappedBy: 'ec')]
    private Collection $note;

    public function __construct()
    {
        $this->note = new ArrayCollection();
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


    public function getUe(): ?UE
    {
        return $this->ue;
    }

    public function setUe(?UE $ue): static
    {
        $this->ue = $ue;

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

    public function getTeacher(): ?Teacher
    {
        return $this->teacher;
    }

    public function setTeacher(?Teacher $teacher): static
    {
        // unset the owning side of the relation if necessary
        if ($teacher === null && $this->teacher !== null) {
            $this->teacher->setEc(null);
        }

        // set the owning side of the relation if necessary
        if ($teacher !== null && $teacher->getEc() !== $this) {
            $teacher->setEc($this);
        }

        $this->teacher = $teacher;

        return $this;
    }


    public function isHasTP(): bool
    {
        return $this->hasTP;
    }

    public function setHasTP(bool $hasTP): static
    {
        $this->hasTP = $hasTP;

        return $this;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNote(): Collection
    {
        return $this->note;
    }

    public function addNote(Note $note): static
    {
        if (!$this->note->contains($note)) {
            $this->note->add($note);
            $note->setEc($this);
        }

        return $this;
    }

    public function removeNote(Note $note): static
    {
        if ($this->note->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getEc() === $this) {
                $note->setEc(null);
            }
        }

        return $this;
    }
    


}
