<?php
// src/EventListener/LoginListener.php
namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Service\VerificationCodeService;

class LoginListener
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private RequestStack $requestStack,
        private VerificationCodeService $verificationCodeService
    ) {}

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();
        $session = $this->requestStack->getSession();

        if ($user instanceof User) {
            // Si l'utilisateur n'a pas encore validé son code
            if (!$session->get('is_fully_authenticated')) {
                // Générer et envoyer le code
                $this->verificationCodeService->generateCode($user);
                $session->set('needs_2fa_verification', true);
                $session->set('pending_user_id', $user->getId());
                
                // Ne pas activer le compte tant que le code n'est pas validé
                return;
            }

            // Si l'authentification est complète
            $user->setIsActive(true);
            $this->entityManager->flush();
        }
    }
}