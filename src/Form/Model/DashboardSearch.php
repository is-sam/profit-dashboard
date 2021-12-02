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
        $this->dateStart = new DateTime();
        $this->dateEnd = new DateTime();
    }

    public function getDateStart(): DateTime
    {
        return $this->dateStart;
    }

    public function setDateStart(DateTime $dateStart): self
    {
        $this->dateStart = $dateStart;
        return $this;
    }

    public function getDateEnd(): DateTime
    {
        return $this->dateEnd;
    }

    public function setDateEnd(DateTime $dateEnd): self
    {
        $this->dateEnd = $dateEnd;
        return $this;
    }
}
