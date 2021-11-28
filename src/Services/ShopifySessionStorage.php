<?php

namespace App\Services;

use Shopify\Auth\Session;
use Shopify\Auth\SessionStorage;

/**
 * Class ShopifySessionStorage.
 */
class ShopifySessionStorage implements SessionStorage
{
    /**
     * Internally handles storing the given session object.
     *
     * @param Session $session The session to store
     *
     * @return bool Whether the operation succeeded
     */
    public function storeSession(Session $session): bool
    {
        //TODO: implement storeSession()
        return false;
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
        //TODO: implement loadSession()
        return null;
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
        //TODO: implement deleteSession()
        return false;
    }
}
