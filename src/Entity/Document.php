<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 */
#[ApiResource(normalizationContext:['groups' => ['read']],
itemOperations:['GET'],
collectionOperations:['GET'])]
class Document
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
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=DocumentType::class, inversedBy="documents")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(["read"])]
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["read"])]
    private $ex;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="IdCardFront")
     */
    private $user1;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="IdCardBack")
     */
    private $user2;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="ProofOfAddress")
     */
    private $user3;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?DocumentType
    {
        return $this->type;
    }

    public function setType(?DocumentType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getEx(): ?string
    {
        return $this->ex;
    }

    public function setEx(string $ex): self
    {
        $this->ex = $ex;

        return $this;
    }

    public function getUser1(): ?User
    {
        return $this->user1;
    }

    public function setUser1(?User $user1): self
    {
        // unset the owning side of the relation if necessary
        if ($user1 === null && $this->user1 !== null) {
            $this->user1->setIdCardFront(null);
        }

        // set the owning side of the relation if necessary
        if ($user1 !== null && $user1->getIdCardFront() !== $this) {
            $user1->setIdCardFront($this);
        }

        $this->user1 = $user1;

        return $this;
    }

    public function getUser2(): ?User
    {
        return $this->user2;
    }

    public function setUser2(?User $user2): self
    {
        // unset the owning side of the relation if necessary
        if ($user2 === null && $this->user2 !== null) {
            $this->user2->setIdCardBack(null);
        }

        // set the owning side of the relation if necessary
        if ($user2 !== null && $user2->getIdCardBack() !== $this) {
            $user2->setIdCardBack($this);
        }

        $this->user2 = $user2;

        return $this;
    }

    public function getUser3(): ?User
    {
        return $this->user3;
    }

    public function setUser3(?User $user3): self
    {
        // unset the owning side of the relation if necessary
        if ($user3 === null && $this->user3 !== null) {
            $this->user3->setProofOfAddress(null);
        }

        // set the owning side of the relation if necessary
        if ($user3 !== null && $user3->getProofOfAddress() !== $this) {
            $user3->setProofOfAddress($this);
        }

        $this->user3 = $user3;

        return $this;
    }
}
