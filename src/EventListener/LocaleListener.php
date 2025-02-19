<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LocaleListener implements EventSubscriberInterface
{
    private $defaultLocale;

    public function __construct(string $defaultLocale = 'en')
    {
        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        // If there is no session, do nothing.
        if (!$request->hasPreviousSession()) {
            return;
        }

        // If the route contains a "locale" parameter (for example, /switch-language/{locale})
        if ($locale = $request->attributes->get('locale')) {
            // Update the session with the new locale
            $request->getSession()->set('_locale', $locale);
            // And update the request locale immediately
            $request->setLocale($locale);
        } else {
            // Otherwise, use the session locale (or the default if not set)
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            // Use a priority of 20 so this runs early in the request
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }


}
