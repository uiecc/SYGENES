<?php

namespace App\Entity;

use App\Repository\UEManagerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UEManagerRepository::class)]
class UEManager extends Responsable
{

    #[ORM\OneToOne(inversedBy: 'uEManager', cascade: ['persist', 'remove'])]
    private ?UE $ue = null;


    public function getUe(): ?UE
    {
        return $this->ue;
    }

    public function setUe(?UE $ue): static
    {
        $this->ue = $ue;

        return $this;
    }
}
