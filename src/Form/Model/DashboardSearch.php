<?php

namespace App\Form\Model;

use DateTime;

class DashboardSearch
{
    protected DateTime $dateStart;
    protected DateTime $dateEnd;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->setDateStart(new DateTime());
        $this->setDateEnd(new DateTime());
    }

    public function getDateStart(): DateTime
    {
        return $this->dateStart;
    }

    public function setDateStart(DateTime $dateStart): self
    {
        $dateStart->setTime(0, 0, 0);
        $this->dateStart = $dateStart;
        return $this;
    }

    public function getDateEnd(): DateTime
    {
        return $this->dateEnd;
    }

    public function setDateEnd(DateTime $dateEnd): self
    {
        $dateEnd->setTime(23, 59, 59);
        $this->dateEnd = $dateEnd;
        return $this;
    }
}
