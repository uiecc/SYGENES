<?php

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeacherRepository::class)]
class Teacher extends Responsable
{

    #[ORM\OneToOne(inversedBy: 'teacher', cascade: ['persist', 'remove'])]
    private ?EC $ec = null;

    public function getEc(): ?EC
    {
        return $this->ec;
    }

    public function setEc(?EC $ec): static
    {
        $this->ec = $ec;

        return $this;
    }
}
