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
     * @ORM\OneToMany(targetEntity=AdSpendAccount::class, mappedBy="shop", orphanRemoval=true)
     */
    private $adSpendAccounts;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->creationDate = new DateTime();
        $this->adSpendSources = new ArrayCollection();
        $this->adSpendAccounts = new ArrayCollection();
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
     * @return Collection|AdSpendAccount[]
     */
    public function getAdSpendAccounts(): Collection
    {
        return $this->adSpendAccounts;
    }

    public function addAdSpendAccount(AdSpendAccount $adSpendAccount): self
    {
        if (!$this->adSpendAccounts->contains($adSpendAccount)) {
            $this->adSpendAccounts[] = $adSpendAccount;
            $adSpendAccount->setShop($this);
        }

        return $this;
    }

    public function removeAdSpendAccount(AdSpendAccount $adSpendAccount): self
    {
        if ($this->adSpendAccounts->removeElement($adSpendAccount)) {
            // set the owning side to null (unless already changed)
            if ($adSpendAccount->getShop() === $this) {
                $adSpendAccount->setShop(null);
            }
        }

        return $this;
    }
}
