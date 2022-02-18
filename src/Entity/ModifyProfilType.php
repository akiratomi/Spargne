<?php

namespace App\Entity;

use App\Repository\ModifyProfilTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModifyProfilTypeRepository::class)
 */
class ModifyProfilType
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
     * @ORM\OneToMany(targetEntity=ModifyProfil::class, mappedBy="type")
     */
    private $modifyProfils;

    public function __construct()
    {
        $this->modifyProfils = new ArrayCollection();
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
     * @return Collection|ModifyProfil[]
     */
    public function getModifyProfils(): Collection
    {
        return $this->modifyProfils;
    }

    public function addModifyProfil(ModifyProfil $modifyProfil): self
    {
        if (!$this->modifyProfils->contains($modifyProfil)) {
            $this->modifyProfils[] = $modifyProfil;
            $modifyProfil->setType($this);
        }

        return $this;
    }

    public function removeModifyProfil(ModifyProfil $modifyProfil): self
    {
        if ($this->modifyProfils->removeElement($modifyProfil)) {
            // set the owning side to null (unless already changed)
            if ($modifyProfil->getType() === $this) {
                $modifyProfil->setType(null);
            }
        }

        return $this;
    }
}
