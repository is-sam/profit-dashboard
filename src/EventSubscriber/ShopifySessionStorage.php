<?php

namespace App\EventSubscriber;

use App\Entity\ShopifySession;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Shopify\Auth\Session;
use Shopify\Auth\SessionStorage;

/**
 * Class ShopifySessionStorage.
 */
class ShopifySessionStorage implements SessionStorage
{
    protected EntityManagerInterface $entityManager;
    protected LoggerInterface $logger;

    /**
     * Class constructor.
     */
    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    /**
     * Internally handles storing the given session object.
     *
     * @param Session $session The session to store
     *
     * @return bool Whether the operation succeeded
     */
    public function storeSession(Session $session): bool
    {
        $shopifySession = (new ShopifySession())
            ->setIdentifier($session->getId())
            ->setShop($session->getShop())
            ->setState($session->getState())
            ->setExpires($session->getExpires())
            ->setOnlineAccessInfo($session->getOnlineAccessInfo())
            ->setAccessToken($session->getAccessToken())
            ->setScope($session->getScope())
            ->setIsOnline($session->isOnline())
        ;
        $this->entityManager->persist($shopifySession);
        $this->entityManager->flush();

        $this->logger->info("Stored session {$session->getId()}");

        return true;
    }

    /**
     * Internally handles loading the given session.
     *
     * @param string $sessionId The id of the session to load
     *
     * @return Session|null The session if it exists, null otherwise
     */
    public function loadSession(string $sessionId)
    {
        $session = $this->entityManager->getRepository(ShopifySession::class)
            ->findOneBy(['identifier' => $sessionId]);

        if (!$session) {
            return null;
        }

        $shopifySession = new Session($session->getIdentifier(), $session->getShop(), $session->getIsOnline(), $session->getState());
        $session->getAccessToken() and $shopifySession->setAccessToken($session->getAccessToken());
        $session->getScope() and $shopifySession->setScope($session->getScope());
        $session->getExpires() and $shopifySession->setExpires($session->getExpires());
        $session->getOnlineAccessInfo() and $shopifySession->setOnlineAccessInfo($session->getOnlineAccessInfo());

        $this->logger->info("Loaded session $sessionId");

        return $shopifySession;
    }

    /**
     * Internally handles deleting the given session.
     *
     * @param string $sessionId The id of the session to delete
     *
     * @return bool Whether the operation succeeded
     */
    public function deleteSession(string $sessionId): bool
    {
        $session = $this->entityManager->getRepository(ShopifySession::class)
            ->findOneBy(['identifier' => $sessionId]);

        if (!$session) {
            return false;
        }

        $this->entityManager->remove($session);
        $this->entityManager->flush();

        $this->logger->info("Deleted session $sessionId");

        return true;
    }
}
