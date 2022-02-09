<?php

namespace App\Entity;

use App\Repository\HireStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HireStatusRepository::class)
 */
class HireStatus
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
     * @ORM\OneToMany(targetEntity=Hire::class, mappedBy="status")
     */
    private $hires;

    public function __construct()
    {
        $this->hires = new ArrayCollection();
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
     * @return Collection|Hire[]
     */
    public function getHires(): Collection
    {
        return $this->hires;
    }

    public function addHire(Hire $hire): self
    {
        if (!$this->hires->contains($hire)) {
            $this->hires[] = $hire;
            $hire->setStatus($this);
        }

        return $this;
    }

    public function removeHire(Hire $hire): self
    {
        if ($this->hires->removeElement($hire)) {
            // set the owning side to null (unless already changed)
            if ($hire->getStatus() === $this) {
                $hire->setStatus(null);
            }
        }

        return $this;
    }
}
