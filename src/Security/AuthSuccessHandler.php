<?php

namespace App\Security;

use App\Entity\Student;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        // Si vous utilisez la vérification en deux étapes
        if ($request->getSession()->get('needs_2fa_verification')) {
            return new RedirectResponse($this->urlGenerator->generate('app_verify_code'));
        }

        // Marquer comme complètement authentifié
        $request->getSession()->set('is_fully_authenticated', true);
        
        // Récupérer l'utilisateur
        $user = $token->getUser();
        
        // Rediriger l'étudiant vers son dashboard
        if ($user instanceof Student) {
            return new RedirectResponse($this->urlGenerator->generate('student_dashboard'));
        }
        
        // Pour les autres types d'utilisateurs, rediriger vers la page d'accueil
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }
}