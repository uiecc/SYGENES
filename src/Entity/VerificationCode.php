<?php

namespace App\Entity;

use App\Repository\VerificationCodeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VerificationCodeRepository::class)]
class VerificationCode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'verificationCodes')]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $code = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $expiresAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isUsed = null;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->code = $this->generateCode();
        $this->expiresAt = new \DateTimeImmutable('+3 minutes');
        $this->isUsed = false;
    }

    private function generateCode(): string
    {
        // Génère un code numérique de 5 chiffres
        return str_pad((string)random_int(0, 99999), 5, '0', STR_PAD_LEFT);
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(?\DateTimeImmutable $expiresAt): static
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function isUsed(): ?bool
    {
        return $this->isUsed;
    }

    public function setIsUsed(?bool $isUsed): static
    {
        $this->isUsed = $isUsed;

        return $this;
    }
}
