<?php

namespace App\Entity;

use App\Repository\ShippingProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ShippingProfileRepository::class)
 */
class ShippingProfile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $name;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $countries = [];

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2)
     */
    private $cost;

    /**
     * @ORM\ManyToOne(targetEntity=Shop::class, inversedBy="shippingProfiles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $shop;

    /**
     * @ORM\ManyToMany(targetEntity=Variant::class, inversedBy="shippingProfiles")
     */
    private $variants;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVariantProfile;

    public function __construct()
    {
        $this->variants = new ArrayCollection();
        $this->isVariantProfile = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCountries(): ?array
    {
        return $this->countries;
    }

    public function setCountries(?array $countries): self
    {
        $this->countries = $countries;

        return $this;
    }

    public function getCost(): ?string
    {
        return $this->cost;
    }

    public function setCost(string $cost): self
    {
        $this->cost = $cost;

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

    /**
     * @return Collection|Variant[]
     */
    public function getVariants(): Collection
    {
        return $this->variants;
    }

    public function addVariant(Variant $variant): self
    {
        if (!$this->variants->contains($variant)) {
            $this->variants[] = $variant;
        }

        return $this;
    }

    public function removeVariant(Variant $variant): self
    {
        $this->variants->removeElement($variant);

        return $this;
    }

    public function getIsVariantProfile(): ?bool
    {
        return $this->isVariantProfile;
    }

    public function setIsVariantProfile(bool $isVariantProfile): self
    {
        $this->isVariantProfile = $isVariantProfile;

        return $this;
    }
}
