<?php

namespace App\Service;

use App\Entity\Shop;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * Class AbstractService.
 */
class AbstractService
{
    protected ?Shop $shop;
    protected Security $security;
    protected EntityManagerInterface $entityManager;

    /**
     * Class constructor.
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Security $security,
    ) {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->shop = $security->getUser();
    }

    public function setShop(Shop $shop)
    {
        $this->shop = $shop;
    }
}
