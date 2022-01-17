<?php

namespace App\Entity;

use App\Repository\AdSpendAccountRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdSpendAccountRepository::class)
 */
class AdSpendAccount
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=AdSpendSource::class, inversedBy="adSpendAccounts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $AdSpendSource;

    /**
     * @ORM\ManyToOne(targetEntity=Shop::class, inversedBy="adSpendAccounts")
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

    public function getAdSpendSource(): ?AdSpendSource
    {
        return $this->AdSpendSource;
    }

    public function setAdSpendSource(?AdSpendSource $AdSpendSource): self
    {
        $this->AdSpendSource = $AdSpendSource;

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
