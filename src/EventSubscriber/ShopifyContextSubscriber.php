<?php

namespace App\EventSubscriber;

use Shopify\Auth\FileSessionStorage;
use Shopify\Context;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ShopifyContextSubscriber implements EventSubscriberInterface
{
    protected ParameterBagInterface $parameterBagInterface;
    protected ShopifySessionStorage $shopifySessionStorage;

    /**
     * Class constructor.
     */
    public function __construct(
        ParameterBagInterface $parameterBagInterface,
        ShopifySessionStorage $shopifySessionStorage
    ) {
        $this->parameterBagInterface = $parameterBagInterface;
        $this->shopifySessionStorage = $shopifySessionStorage;
    }

    public function onKernelController(ControllerEvent $event)
    {
        Context::initialize(
            $this->parameterBagInterface->get('shopify.api.key'),
            $this->parameterBagInterface->get('shopify.api.secret'),
            $this->parameterBagInterface->get('shopify.app.scopes'),
            $this->parameterBagInterface->get('shopify.app.hostname'),
            $this->shopifySessionStorage,
            '2021-10',
            true,
            false,
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
