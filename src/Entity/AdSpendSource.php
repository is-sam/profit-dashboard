<?php

namespace App\Entity;

use App\Repository\AdSpendSourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdSpendSourceRepository::class)
 */
class AdSpendSource
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
     * @ORM\OneToMany(targetEntity=AdSpendAccount::class, mappedBy="AdSpendSource")
     */
    private $adSpendAccounts;

    public function __construct()
    {
        $this->adSpendAccounts = new ArrayCollection();
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
            $adSpendAccount->setAdSpendSource($this);
        }

        return $this;
    }

    public function removeAdSpendAccount(AdSpendAccount $adSpendAccount): self
    {
        if ($this->adSpendAccounts->removeElement($adSpendAccount)) {
            // set the owning side to null (unless already changed)
            if ($adSpendAccount->getAdSpendSource() === $this) {
                $adSpendAccount->setAdSpendSource(null);
            }
        }

        return $this;
    }
}
