<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CardRepository;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CardRepository::class)
 */
#[ApiResource(normalizationContext:['groups' => ['read']],
itemOperations:["GET" => ['method' => 'GET', "security"=>"is_granted('ROLE_DIRECTOR') or object.owner == user"]],
collectionOperations:['GET'=>["security"=>"is_granted('ROLE_DIRECTOR') or object.owner == user"]] 
)]
class Card
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
    private $number;

    /**
     * @ORM\Column(type="string", length=3)
     */
    #[Groups(["read"])]
    private $crypto;

    /**
     * @ORM\Column(type="date")
     */
    #[Groups(["read"])]
    private $creationDate;

    /**
     * @ORM\ManyToOne(targetEntity=CardType::class, inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(["read"])]
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    public $owner;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     */
    private $account;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getCrypto(): ?string
    {
        return $this->crypto;
    }

    public function setCrypto(string $crypto): self
    {
        $this->crypto = $crypto;

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

    public function getType(): ?CardType
    {
        return $this->type;
    }

    public function setType(?CardType $type): self
    {
        $this->type = $type;

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

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }
}
