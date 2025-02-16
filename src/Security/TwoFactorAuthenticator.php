<?php
// src/Security/TwoFactorAuthenticator.php
namespace App\Security;

use App\Service\VerificationCodeService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
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
        try {
            // Récupérer l'utilisateur
            $user = $token->getUser();
            
            // Générer et envoyer le code de vérification
            $verificationCode = $this->verificationCodeService->generateCode($user);
            
            // Debug - ajoutez ces lignes temporairement
            dump("Code généré : " . $verificationCode->getCode());
            dump("Pour l'utilisateur : " . $user->getEmail());
    
            // Marquer que l'utilisateur a besoin de vérification
            $request->getSession()->set('needs_2fa_verification', true);
    
            return new RedirectResponse($this->router->generate('app_verify_code'));
        } catch (\Exception $e) {
            // Debug - log l'erreur
            dump("Erreur lors de l'envoi du code : " . $e->getMessage());
            throw $e;
        }
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