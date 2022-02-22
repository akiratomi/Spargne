<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AccountRepository::class)
 */
class Account
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
    private $num;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $iban;

    /**
     * @ORM\Column(type="float")
     */
    private $balance;

    /**
     * @ORM\Column(type="date")
     */
    private $creation_date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="accounts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

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
     * @ORM\ManyToOne(targetEntity=AccountType::class, inversedBy="accounts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Beneficiary::class, mappedBy="account")
     */
    private $beneficiaries;

    public function __construct()
    {
        $this->beneficiaries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNum(): ?string
    {
        return $this->num;
    }

    public function setNum(string $num): self
    {
        $this->num = $num;

        return $this;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(string $iban): self
    {
        $this->iban = $iban;

        return $this;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeInterface $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

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

    public function getType(): ?AccountType
    {
        return $this->type;
    }

    public function setType(?AccountType $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Beneficiary[]
     */
    public function getBeneficiaries(): Collection
    {
        return $this->beneficiaries;
    }

    public function addBeneficiary(Beneficiary $beneficiary): self
    {
        if (!$this->beneficiaries->contains($beneficiary)) {
            $this->beneficiaries[] = $beneficiary;
            $beneficiary->setAccount($this);
        }

        return $this;
    }

    public function removeBeneficiary(Beneficiary $beneficiary): self
    {
        if ($this->beneficiaries->removeElement($beneficiary)) {
            // set the owning side to null (unless already changed)
            if ($beneficiary->getAccount() === $this) {
                $beneficiary->setAccount(null);
            }
        }

        return $this;
    }
}
