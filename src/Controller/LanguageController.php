<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LanguageController extends AbstractController
{
    #[Route('/switch-language/{locale}', name: 'switch_language')]
    public function switchLanguage(Request $request, string $locale): Response
    {
        // Set the new locale in the session (the listener will also pick it up)
        $request->getSession()->set('_locale', $locale);
        
        // Redirect to the previous page or the homepage if no referer exists
        $referer = $request->headers->get('referer');
        return $this->redirect($referer ?? '/');
    }
      
}
