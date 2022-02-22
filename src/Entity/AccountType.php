<?php

namespace App\Entity;

use App\Repository\AccountTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccountTypeRepository::class)
 */
class AccountType
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
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $limitBalance;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $overdraft;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rate;

    /**
     * @ORM\OneToMany(targetEntity=Account::class, mappedBy="type", orphanRemoval=true)
     */
    private $accounts;

    public function __construct()
    {
        $this->accounts = new ArrayCollection();
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

    public function getLimitBalance(): ?int
    {
        return $this->limitBalance;
    }

    public function setLimitBalance(?int $limitBalance): self
    {
        $this->limitBalance = $limitBalance;

        return $this;
    }

    public function getOverdraft(): ?int
    {
        return $this->overdraft;
    }

    public function setOverdraft(?int $overdraft): self
    {
        $this->overdraft = $overdraft;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(?int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return Collection|Account[]
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    public function addAccount(Account $account): self
    {
        if (!$this->accounts->contains($account)) {
            $this->accounts[] = $account;
            $account->setType($this);
        }

        return $this;
    }

    public function removeAccount(Account $account): self
    {
        if ($this->accounts->removeElement($account)) {
            // set the owning side to null (unless already changed)
            if ($account->getType() === $this) {
                $account->setType(null);
            }
        }

        return $this;
    }
}
