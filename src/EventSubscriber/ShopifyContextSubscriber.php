<?php

namespace App\EventSubscriber;

use Shopify\Auth\FileSessionStorage;
use Shopify\Context;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ShopifyContextSubscriber implements EventSubscriberInterface
{
    public function onKernelController(ControllerEvent $event)
    {
        Context::initialize(
            $_ENV['SHOPIFY_API_KEY'],
            $_ENV['SHOPIFY_API_SECRET'],
            $_ENV['SHOPIFY_APP_SCOPES'],
            $_ENV['SHOPIFY_APP_HOST_NAME'],
            new FileSessionStorage('/tmp/php_sessions'),
            '2021-04',
            true,
            false,
        );
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
