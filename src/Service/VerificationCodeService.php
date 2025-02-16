<?php
// src/Service/VerificationCodeService.php
namespace App\Service;

use App\Entity\User;
use App\Entity\VerificationCode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class VerificationCodeService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MailerInterface $mailer,
        private Environment $twig  // Ajoutez Twig
    ) {}

    public function generateCode(User $user): VerificationCode
    {
        try {
            $code = new VerificationCode($user);
            $this->entityManager->persist($code);
            $this->entityManager->flush();
    
            // Debug - log la création du code
            error_log("Code généré : " . $code->getCode() . " pour " . $user->getEmail());
    
            // Envoyer par email
            $this->sendCodeByEmail($user, $code->getCode());
    
            return $code;
        } catch (\Exception $e) {
            // Debug - log l'erreur
            error_log("Erreur dans generateCode : " . $e->getMessage());
            throw $e;
        }
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
        try {
            error_log("Tentative d'envoi d'email à " . $user->getEmail());

            $email = (new Email())
                ->from('uiecc@esign.cm')  // Votre adresse email configurée
                ->to($user->getEmail())
                ->subject('Code de vérification - SYGENES')
                ->html(
                    $this->twig->render(
                        'emails/verification_code.html.twig',
                        [
                            'user' => $user,
                            'code' => $code
                        ]
                    )
                );

            $this->mailer->send($email);
            error_log("Email envoyé avec succès à " . $user->getEmail());
        } catch (\Exception $e) {
            dd($e->getMessage());
            // Log l'erreur ou gérez-la comme vous le souhaitez
            throw new \Exception('Erreur lors de l\'envoi de l\'email: ' . $e->getMessage());
        }
    }

    private function sendCodeBySMS(User $user, string $code): void
    {
        // Implémentez l'envoi SMS avec un service comme Twilio
    }
}