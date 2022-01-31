<?php

namespace App\Entity;

use App\Repository\VariantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VariantRepository::class)
 */
class Variant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $identifier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $cost;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="variants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @ORM\ManyToMany(targetEntity=ShippingProfile::class, mappedBy="variants")
     */
    private $shippingProfiles;

    public function __construct()
    {
        $this->shippingProfiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(?float $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return Collection|ShippingProfile[]
     */
    public function getShippingProfiles(): Collection
    {
        return $this->shippingProfiles;
    }

    public function addShippingProfile(ShippingProfile $shippingProfile): self
    {
        if (!$this->shippingProfiles->contains($shippingProfile)) {
            $this->shippingProfiles[] = $shippingProfile;
            $shippingProfile->addVariant($this);
        }

        return $this;
    }

    public function removeShippingProfile(ShippingProfile $shippingProfile): self
    {
        if ($this->shippingProfiles->removeElement($shippingProfile)) {
            $shippingProfile->removeVariant($this);
        }

        return $this;
    }
}
