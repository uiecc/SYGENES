<?php

namespace App\Entity;

use App\Repository\ResponsableRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResponsableRepository::class)]
class Responsable extends User
{
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $codeResp = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $function = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $department = null;

    public function getCodeResp(): ?string
    {
        return $this->codeResp;
    }

    public function setCodeResp(?string $codeResp): static
    {
        $this->codeResp = $codeResp;
        return $this;
    }

    public function getFunction(): ?string
    {
        return $this->function;
    }

    public function setFunction(?string $function): static
    {
        $this->function = $function;
        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): static
    {
        $this->department = $department;
        return $this;
    }
}