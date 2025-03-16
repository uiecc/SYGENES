<?php
// src/Security/TwoFactorAuthenticator.php
namespace App\Security;

use App\Entity\Administrator;
use App\Entity\FieldManager;
use App\Entity\LevelManager;
use App\Entity\SchoolManager;
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
use Symfony\Component\Security\Http\Util\TargetPathTrait;
class TwoFactorAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;
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
        
        // Si l'authentification à deux facteurs est complète
        if ($session->get('is_fully_authenticated')) {
            // Nettoyer la session des variables temporaires
            $session->remove('needs_2fa_verification');
            $session->remove('pending_user_id');
            
            // Déterminer la redirection en fonction du type d'utilisateur
            if ($user instanceof Student) {
                return new RedirectResponse($this->router->generate('student_dashboard'));
            } 
            else if ($user instanceof LevelManager) {
                return new RedirectResponse($this->router->generate('level_manager_dashboard'));
            }
            else if ($user instanceof FieldManager) {
                return new RedirectResponse($this->router->generate('field_manager_dashboard'));
            }
            else if ($user instanceof SchoolManager) {
                return new RedirectResponse($this->router->generate('school_manager_dashboard'));
            }
            else if ($user instanceof Administrator) {
                return new RedirectResponse($this->router->generate('admin_dashboard'));
            }
            
            // Redirection par défaut si le type d'utilisateur n'est pas reconnu
            if ($targetPath = $this->getTargetPath($session, $firewallName)) {
                return new RedirectResponse($targetPath);
            }
            
            // Redirection finale par défaut
            return new RedirectResponse($this->router->generate('app_home'));
        }
        
        // Si l'authentification à deux facteurs est nécessaire
        
        // Générer et envoyer le code de vérification
        $this->verificationCodeService->generateCode($user);
        
        // Marquer que la vérification est nécessaire
        $session->set('needs_2fa_verification', true);
        $session->remove('is_fully_authenticated');
        
        // Garder l'ID de l'utilisateur pour la vérification
        $session->set('pending_user_id', $user->getId());
        
        // Rediriger vers la page de vérification
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
