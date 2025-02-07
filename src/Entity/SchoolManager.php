<?php

namespace App\Entity;

use App\Repository\SchoolManagerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SchoolManagerRepository::class)]
class SchoolManager extends Responsable
{

    #[ORM\OneToOne(inversedBy: 'schoolManager', cascade: ['persist', 'remove'])]
    private ?School $school = null;

    public function getSchool(): ?School
    {
        return $this->school;
    }

    public function setSchool(?School $school): static
    {
        $this->school = $school;

        return $this;
    }
}
