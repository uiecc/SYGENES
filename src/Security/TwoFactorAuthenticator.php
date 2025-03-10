<?php
// src/Security/TwoFactorAuthenticator.php
namespace App\Security;

use App\Entity\Student;
use App\Service\VerificationCodeService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;

class TwoFactorAuthenticator extends AbstractLoginFormAuthenticator
{
    public function __construct(
        private RouterInterface $router,
        private VerificationCodeService $verificationCodeService
    ) {}

    public function supports(Request $request): bool
    {
        return $request->isMethod('POST') && $this->getLoginUrl($request) === $request->getPathInfo();
    }

    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('_username', '');
        $password = $request->request->get('_password', '');

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $username);

        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($password)
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $session = $request->getSession();
        $user = $token->getUser();
        
        // Si l'authentification est déjà complète
        if ($session->get('is_fully_authenticated')) {
            // Si l'utilisateur est un étudiant, rediriger vers le tableau de bord étudiant
            if ($user instanceof Student) {
                return new RedirectResponse($this->router->generate('student_dashboard'));
            }
            
            // Sinon, rediriger vers la page d'accueil ou le chemin cible
            if ($targetPath = $this->getTargetPath($session, $firewallName)) {
                return new RedirectResponse($targetPath);
            }
            return new RedirectResponse($this->router->generate('app_home'));
        }
    
        // Générer et envoyer le code
        $this->verificationCodeService->generateCode($user);
        
        // Marquer que la vérification est nécessaire
        $session->set('needs_2fa_verification', true);
        $session->remove('is_fully_authenticated');
        
        // Garder l'ID de l'utilisateur pour la vérification
        $session->set('pending_user_id', $user->getId());
    
        // Rediriger vers la vérification
        return new RedirectResponse($this->router->generate('app_verify_code'));
    }
    
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        if ($request->hasSession()) {
            $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);
        }

        return new RedirectResponse($this->router->generate('app_login'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate('app_login');
    }
}