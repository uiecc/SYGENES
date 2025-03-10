<?php
// src/EventListener/SecurityListener.php
namespace App\EventListener;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class SecurityListener
{
    private const ALLOWED_ROUTES = [
        'app_login',
        'app_verify_code',
        'app_logout',
        'app_register_student',
        'app_register_responsable',
        '_wdt',  // Route de la toolbar Symfony
        '_profiler',  // Routes du profiler Symfony
        '_previews'
    ];

    public function __construct(
        private Security $security,
        private RouterInterface $router,
        private RequestStack $requestStack
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $route = $request->attributes->get('_route');

        // Si la route est autorisée, on ne fait rien
        if (in_array($route, self::ALLOWED_ROUTES) || str_starts_with($route, '_')) {
            return;
        }

        // Vérifier si une session est disponible
        if (!$request->hasSession()) {
            return;
        }

        $session = $this->requestStack->getSession();
        if (!$session->isStarted()) {
            return;
        }

        $user = $this->security->getUser();
        if ($user) {
            // Si l'utilisateur est connecté mais n'a pas validé son code
            if (!$session->get('is_fully_authenticated')) {
                $event->setResponse(new RedirectResponse($this->router->generate('app_verify_code')));
            }
        }
    }
}