<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AccountRepository::class)
 * @ORM\Table(name="`account`")
 */
#[ApiResource(normalizationContext:['groups' => ['read']],
itemOperations:["GET" => ['method' => 'GET', "security"=>"is_granted('ROLE_DIRECTOR') or object == user"]],
collectionOperations:['GET'=>["security"=>"is_granted('ROLE_DIRECTOR') or object == user"]] 
)]
class Account
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(["read"])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["read"])]
    private $num;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["read"])]
    private $iban;

    /**
     * @ORM\Column(type="float")
     */
    #[Groups(["read"])]
    private $balance;

    /**
     * @ORM\Column(type="date")
     */
    #[Groups(["read"])]
    private $creation_date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="accounts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    #[Groups(["read"])]
    private $limitBalance;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    #[Groups(["read"])]
    private $overdraft;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    #[Groups(["read"])]
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

    /**
     * @ORM\OneToMany(targetEntity=Transferts::class, mappedBy="fromAccount")
     */
    private $transfertsOut;

    /**
     * @ORM\OneToMany(targetEntity=Transferts::class, mappedBy="destinationAccount")
     */
    private $transfertsIn;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Card::class, mappedBy="account", orphanRemoval=true)
     */
    private $cards;

    public function __construct()
    {
        $this->beneficiaries = new ArrayCollection();
        $this->transfertsOut = new ArrayCollection();
        $this->transfertsIn = new ArrayCollection();
        $this->cards = new ArrayCollection();
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

    /**
     * @return Collection|Transferts[]
     */
    public function getTransfertsOut(): Collection
    {
        return $this->transfertsOut;
    }

    public function addTransfertsOut(Transferts $transfertsOut): self
    {
        if (!$this->transfertsOut->contains($transfertsOut)) {
            $this->transfertsOut[] = $transfertsOut;
            $transfertsOut->setFromAccount($this);
        }

        return $this;
    }

    public function removeTransfertsOut(Transferts $transfertsOut): self
    {
        if ($this->transfertsOut->removeElement($transfertsOut)) {
            // set the owning side to null (unless already changed)
            if ($transfertsOut->getFromAccount() === $this) {
                $transfertsOut->setFromAccount(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transferts[]
     */
    public function getTransfertsIn(): Collection
    {
        return $this->transfertsIn;
    }

    public function addTransfertsIn(Transferts $transfertsIn): self
    {
        if (!$this->transfertsIn->contains($transfertsIn)) {
            $this->transfertsIn[] = $transfertsIn;
            $transfertsIn->setDestinationAccount($this);
        }

        return $this;
    }

    public function removeTransfertsIn(Transferts $transfertsIn): self
    {
        if ($this->transfertsIn->removeElement($transfertsIn)) {
            // set the owning side to null (unless already changed)
            if ($transfertsIn->getDestinationAccount() === $this) {
                $transfertsIn->setDestinationAccount(null);
            }
        }

        return $this;
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
     * @return Collection|Card[]
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setAccount($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getAccount() === $this) {
                $card->setAccount(null);
            }
        }

        return $this;
    }
}
