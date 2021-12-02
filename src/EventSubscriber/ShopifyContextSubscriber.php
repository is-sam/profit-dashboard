<?php

namespace App\EventSubscriber;

use Shopify\Auth\FileSessionStorage;
use Shopify\Context;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ShopifyContextSubscriber implements EventSubscriberInterface
{
    protected ParameterBagInterface $parameterBagInterface;

    /**
     * Class constructor.
     */
    public function __construct(ParameterBagInterface $parameterBagInterface)
    {
        $this->parameterBagInterface = $parameterBagInterface;
    }

    public function onKernelController(ControllerEvent $event)
    {
        Context::initialize(
            $this->parameterBagInterface->get('shopify.api.key'),
            $this->parameterBagInterface->get('shopify.api.secret'),
            $this->parameterBagInterface->get('shopify.app.scopes'),
            $this->parameterBagInterface->get('shopify.app.hostname'),
            new FileSessionStorage('/tmp/php_sessions'),
            '2021-10',
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
