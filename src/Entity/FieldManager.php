<?php

namespace App\Entity;

use App\Repository\FieldManagerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FieldManagerRepository::class)]
class FieldManager extends Responsable
{

    #[ORM\OneToOne(inversedBy: 'fieldManager', cascade: ['persist', 'remove'])]
    private ?Field $field = null;

    public function getField(): ?Field
    {
        return $this->field;
    }

    public function setField(?Field $field): static
    {
        $this->field = $field;

        return $this;
    }
}
