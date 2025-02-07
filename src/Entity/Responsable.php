<?php

namespace App\Entity;

use App\Repository\ResponsableRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResponsableRepository::class)]
class Responsable extends User

{

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $codeResp = null;


    public function getCodeResp(): ?string
    {
        return $this->codeResp;
    }

    public function setCodeResp(?string $codeResp): static
    {
        $this->codeResp = $codeResp;

        return $this;
    }
}
