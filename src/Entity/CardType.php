<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CardTypeRepository;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CardTypeRepository::class)
 */
#[ApiResource(normalizationContext:['groups' => ['read']],
itemOperations:["GET" => ['method' => 'GET', "security"=>"is_granted('ROLE_DIRECTOR') or object == user"]],
collectionOperations:['GET'=>["security"=>"is_granted('ROLE_DIRECTOR') or object == user"]] 
)]
class CardType
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
     * @ORM\OneToMany(targetEntity=Card::class, mappedBy="type", orphanRemoval=true)
     */
    private $cards;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["read"])]
    private $imgPath;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
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
            $card->setType($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getType() === $this) {
                $card->setType(null);
            }
        }

        return $this;
    }

    public function getImgPath(): ?string
    {
        return $this->imgPath;
    }

    public function setImgPath(string $imgPath): self
    {
        $this->imgPath = $imgPath;

        return $this;
    }
}
