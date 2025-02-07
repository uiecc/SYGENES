<?php

namespace App\Entity;

use App\Repository\LevelManagerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LevelManagerRepository::class)]
class LevelManager extends Responsable
{

    #[ORM\OneToOne(inversedBy: 'levelManager', cascade: ['persist', 'remove'])]
    private ?Level $level = null;

    public function getLevel(): ?Level
    {
        return $this->level;
    }

    public function setLevel(?Level $level): static
    {
        $this->level = $level;

        return $this;
    }
}
