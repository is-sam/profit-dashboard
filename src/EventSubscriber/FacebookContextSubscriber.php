<?php

namespace App\EventSubscriber;

use FacebookAds\Api;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class FacebookContextSubscriber implements EventSubscriberInterface
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
        Api::init(
            $this->parameterBagInterface->get('facebook.app.id'),
            $this->parameterBagInterface->get('facebook.app.secret'),
            $this->parameterBagInterface->get('facebook.access.token')
        );
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
