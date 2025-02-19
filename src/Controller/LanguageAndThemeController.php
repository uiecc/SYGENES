<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LanguageAndThemeController extends AbstractController
{
    #[Route('/switch-language1/{locale}', name: 'switch_language')]
    public function switchLanguage1(Request $request, string $locale): Response
    {
        $request->getSession()->set('_locale', $locale);

        return new RedirectResponse($request->headers->get('referer') ?? '/');
    }

    #[Route('/change-language/{locale}', name: 'change_language')]
    public function changeLanguage(Request $request, string $locale): RedirectResponse
    {
        // Store the new locale in the session.
        $request->getSession()->set('_locale', $locale);

        // Redirect back to the referring page or home if not available.
        $referer = $request->headers->get('referer') ?? $this->generateUrl('home');
        return $this->redirect($referer);
    }

    #[Route('/switch-theme/{theme}', name: 'switch_theme')]
    public function switchTheme(Request $request, string $theme): JsonResponse
    {
        $request->getSession()->set('theme', $theme);
        return new JsonResponse(['success' => true]);
    }
}
