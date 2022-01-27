<?php

namespace App\Entity;

use App\Repository\MarketingAccountRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MarketingAccountRepository::class)
 */
class MarketingAccount
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=MarketingSource::class, inversedBy="marketingAccounts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $marketingSource;

    /**
     * @ORM\ManyToOne(targetEntity=Shop::class, inversedBy="marketingAccounts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shop;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $data = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarketingSource(): ?MarketingSource
    {
        return $this->MarketingSource;
    }

    public function setMarketingSource(?MarketingSource $marketingSource): self
    {
        $this->marketingSource = $marketingSource;

        return $this;
    }

    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    public function setShop(?Shop $shop): self
    {
        $this->shop = $shop;

        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): self
    {
        $this->data = $data;

        return $this;
    }
}
