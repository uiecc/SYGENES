<?php
// src/Service/VerificationCodeService.php
namespace App\Service;

use App\Entity\User;
use App\Entity\VerificationCode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class VerificationCodeService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MailerInterface $mailer
    ) {}

    public function generateCode(User $user): VerificationCode
    {
        $code = new VerificationCode($user);
        $this->entityManager->persist($code);
        $this->entityManager->flush();

        // Envoyer par email
        $this->sendCodeByEmail($user, $code->getCode());
        // Envoyer par SMS (à implémenter avec un service SMS)
        $this->sendCodeBySMS($user, $code->getCode());

        return $code;
    }

    public function isCodeValid(User $user, string $code): bool
    {
        $verificationCode = $this->entityManager
            ->getRepository(VerificationCode::class)
            ->findOneBy([
                'user' => $user,
                'code' => $code,
                'isUsed' => false
            ]);

        if (!$verificationCode) {
            return false;
        }

        if ($verificationCode->getExpiresAt() < new \DateTimeImmutable()) {
            return false;
        }

        $verificationCode->setIsUsed(true);
        $this->entityManager->flush();

        return true;
    }

    private function sendCodeByEmail(User $user, string $code): void
    {
        $email = (new Email())
            ->from('no-reply@your-domain.com')
            ->to($user->getEmail())
            ->subject('Code de vérification')
            ->html("<p>Votre code de vérification est : $code</p><p>Il expirera dans 3 minutes.</p>");

        $this->mailer->send($email);
    }

    private function sendCodeBySMS(User $user, string $code): void
    {
        // Implémentez l'envoi SMS avec un service comme Twilio
    }
}