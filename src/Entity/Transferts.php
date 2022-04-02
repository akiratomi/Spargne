<?php

namespace App\Entity;

use App\Repository\TransfertsRepository;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=TransfertsRepository::class)
 */
#[ApiResource(
itemOperations:["GET" => ['method' => 'GET', "security"=>"is_granted('ROLE_DIRECTOR') or object == user"]],
collectionOperations:["GET" => [
    'method' => 'GET', 
    "security"=>"is_granted('ROLE_DIRECTOR') or object.fromAccount.owner == user or object.destinationAccount.owner == user",
    'path' => '/transferts/{id}/{first}/{last}', 
    'route_name' => 'GetTransfertsByAccount',
    'filters' => [],
    'pagination_enabled' => false,
    'openapi_context' => [
        'summary' => "Récupère les transactions d'un compte",
        'parameters' => [
            [
                'in' => 'path',
                'name' => 'id',
                'description' => 'Identifiant de l\'utilisateur',
                'required' => true,
                'schema' => [
                    'type' => 'integer'
                ]
            ],[
                'in' => 'path',
                'name' => 'first',
                'description' => 'Identifiant de l\'utilisateur',
                'required' => true,
                'schema' => [
                    'type' => 'integer'
                ]
            ],[
                'in' => 'path',
                'name' => 'last',
                'description' => 'Identifiant de l\'utilisateur',
                'required' => true,
                'schema' => [
                    'type' => 'integer'
                ]
            ]
        ],
    ],
    
]] 
)]
class Transferts
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(["read"])]
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    #[Groups(["read"])]
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="transfertsOut")
     */
    private $fromAccount;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="transfertsIn")
     */
    private $destinationAccount;

    /**
     * @ORM\ManyToOne(targetEntity=TransfertsType::class, inversedBy="transferts")
     */

    private $type;

    /**
     * @ORM\Column(type="float")
     */
    #[Groups(["read"])]
    private $amount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getFromAccount(): ?Account
    {
        return $this->fromAccount;
    }

    public function setFromAccount(?Account $fromAccount): self
    {
        $this->fromAccount = $fromAccount;

        return $this;
    }

    public function getDestinationAccount(): ?Account
    {
        return $this->destinationAccount;
    }

    public function setDestinationAccount(?Account $destinationAccount): self
    {
        $this->destinationAccount = $destinationAccount;

        return $this;
    }

    public function getType(): ?TransfertsType
    {
        return $this->type;
    }

    public function setType(?TransfertsType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
}
