<?php
// src/Security/LogoutHandler.php
namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LogoutHandler implements EventSubscriberInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {}

    public static function getSubscribedEvents(): array
    {
        return [LogoutEvent::class => 'onLogout'];
    }

    public function onLogout(LogoutEvent $event): void
    {
        $user = $event->getToken()->getUser();
        if ($user instanceof User) {
            $user->setIsActive(false);
            $this->entityManager->flush();
        }
    }
}