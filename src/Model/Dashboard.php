<?php

namespace App\Model;

use App\Entity\Shop;
use DateTime;

/**
 * Dashboard Class
 */
class Dashboard
{
    public function __construct(
        protected string $title,
        protected Shop $shop,
        protected DateTime $date,
    ) {}
}
