<?php
// src/EventListener/LogoutListener.php
namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutListener
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function onSymfonyComponentSecurityHttpEventLogoutEvent(LogoutEvent $event): void
    {
        $user = $event->getToken()->getUser();

        if ($user instanceof User) {
            $user->setIsActive(false);
            $this->entityManager->flush();
        }
    }
}