<?php

namespace App\EventSubscriber;

use Shopify\Context;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
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
        $this->initShopifyAPI();
    }

    public function onConsoleCommand(ConsoleCommandEvent $event)
    {
        $this->initShopifyAPI();
    }

    public function initShopifyAPI()
    {
        Context::initialize(
            $this->parameterBagInterface->get('shopify.api.key'),
            $this->parameterBagInterface->get('shopify.api.secret'),
            $this->parameterBagInterface->get('shopify.app.scopes'),
            $this->parameterBagInterface->get('shopify.app.hostname'),
            $this->shopifySessionStorage,
            $this->parameterBagInterface->get('shopify.api.version'),
            true,
            false,
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
            ConsoleEvents::COMMAND => 'onConsoleCommand',
        ];
    }
}
