<?php

namespace App\Entity;

use App\Repository\BeneficiaryRepository;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=BeneficiaryRepository::class)
 */
#[ApiResource(normalizationContext:['groups' => ['read']],
itemOperations:["GET" => ['method' => 'GET', "security"=>"is_granted('ROLE_DIRECTOR') or object.owner == user"],
],
collectionOperations:['GET'=>["security"=>"is_granted('ROLE_DIRECTOR') or object.owner == user"]] 
)]
class Beneficiary
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(["read"])]
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="beneficiaries")
     * @ORM\JoinColumn(nullable=false)
     */
    public $owner;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="beneficiaries")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(["read"])]
    private $account;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["read"])]
    private $name;

    /**
     * @ORM\Column(type="date")
     */
    #[Groups(["read"])]
    private $added_date;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddedDate(): ?\DateTimeInterface
    {
        return $this->added_date;
    }

    public function setAddedDate(\DateTimeInterface $added_date): self
    {
        $this->added_date = $added_date;

        return $this;
    }
}
