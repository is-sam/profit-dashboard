<?php

namespace App\Entity;

use App\Repository\ShopRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=ShopRepository::class)
 */
class Shop implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="shop")
     */
    private $products;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $accessToken;

    /**
     * @ORM\OneToMany(targetEntity=MarketingAccount::class, mappedBy="shop", orphanRemoval=true)
     */
    private $marketingAccounts;

    /**
     * @ORM\OneToMany(targetEntity=CustomCost::class, mappedBy="shop")
     */
    private $customCosts;

    /**
     * @ORM\OneToMany(targetEntity=ShippingProfile::class, mappedBy="shop", orphanRemoval=true)
     */
    private $shippingProfiles;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->creationDate = new DateTime();
        $this->marketingAccounts = new ArrayCollection();
        $this->customCosts = new ArrayCollection();
        $this->shippingProfiles = new ArrayCollection();
    }

    public function getUserIdentifier(): string
    {
        return $this->url;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
        return;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setShop($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getShop() === $this) {
                $product->setShop(null);
            }
        }

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(?string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @return Collection|MarketingAccount[]
     */
    public function getMarketingAccounts(): Collection
    {
        return $this->marketingAccounts;
    }

    public function addMarketingAccount(MarketingAccount $marketingAccount): self
    {
        if (!$this->marketingAccounts->contains($marketingAccount)) {
            $this->marketingAccounts[] = $marketingAccount;
            $marketingAccount->setShop($this);
        }

        return $this;
    }

    public function removeMarketingAccount(MarketingAccount $marketingAccount): self
    {
        if ($this->marketingAccounts->removeElement($marketingAccount)) {
            // set the owning side to null (unless already changed)
            if ($marketingAccount->getShop() === $this) {
                $marketingAccount->setShop(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CustomCost[]
     */
    public function getCustomCosts(): Collection
    {
        return $this->customCosts;
    }

    public function addCustomCost(CustomCost $customCost): self
    {
        if (!$this->customCosts->contains($customCost)) {
            $this->customCosts[] = $customCost;
            $customCost->setShop($this);
        }

        return $this;
    }

    public function removeCustomCost(CustomCost $customCost): self
    {
        if ($this->customCosts->removeElement($customCost)) {
            // set the owning side to null (unless already changed)
            if ($customCost->getShop() === $this) {
                $customCost->setShop(null);
            }
        }

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
            $shippingProfile->setShop($this);
        }

        return $this;
    }

    public function removeShippingProfile(ShippingProfile $shippingProfile): self
    {
        if ($this->shippingProfiles->removeElement($shippingProfile)) {
            // set the owning side to null (unless already changed)
            if ($shippingProfile->getShop() === $this) {
                $shippingProfile->setShop(null);
            }
        }

        return $this;
    }
}
