<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\Custom\GetUserByUuid;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
#[ApiResource(normalizationContext:['groups' => ['read']],
itemOperations:["GET" => ['method' => 'GET', 'path' => '/users/{uuid}', "security"=>"is_granted('ROLE_DIRECTOR') or object == user"],
    'GETBYUUID' => ['method' => 'GET', 'path' => '/users/getByUuid/{uuid}', "security"=>"is_granted('ROLE_DIRECTOR') or object == user"],

],
collectionOperations:['GET'=>["security"=>"is_granted('ROLE_DIRECTOR') or object == user"]] 
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @ApiProperty(identifier=false)
     */
    #[Groups(["read"])]
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @ApiProperty(identifier=true)
     */
    #[Groups(["read"])]
    private $uuid;

    /**
     * @ORM\Column(type="json")
     */
    #[Groups(["read"])]
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["read"])]
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["read"])]
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["read"])]
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["read"])]
    private $postalAddress;

    /**
     * @ORM\Column(type="date")
     */
    #[Groups(["read"])]
    private $registerDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $profilPicture;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["read"])]
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["read"])]
    private $country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["read"])]
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(["read"])]
    private $phoneNumber;

    /**
     * @ORM\OneToOne(targetEntity=Document::class, inversedBy="user1", cascade={"persist", "remove"})
     */
    private $IdCardFront;

    /**
     * @ORM\OneToOne(targetEntity=Document::class, inversedBy="user2", cascade={"persist", "remove"})
     */
    private $IdCardBack;

    /**
     * @ORM\OneToOne(targetEntity=Document::class, inversedBy="user3", cascade={"persist", "remove"})
     */
    private $ProofOfAddress;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="customers")
     */
    #[Groups(["read"])]
    private $advisor;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="advisor")
     */
    #[Groups(["read"])]
    private $customers;

    /**
     * @ORM\OneToMany(targetEntity=ModifyProfil::class, mappedBy="user", orphanRemoval=true)
     */
    private $modifyProfils;

    /**
     * @ORM\Column(type="boolean")
     */
    #[Groups(["read"])]
    private $firstMdp;

    /**
     * @ORM\OneToMany(targetEntity=Account::class, mappedBy="owner", orphanRemoval=true)
     */
    #[Groups(["read"])]
    private $accounts;

    /**
     * @ORM\OneToMany(targetEntity=Beneficiary::class, mappedBy="owner", orphanRemoval=true)
     */
    #[Groups(["read"])]
    private $beneficiaries;

    /**
     * @ORM\OneToMany(targetEntity=Card::class, mappedBy="owner", orphanRemoval=true)
     */
    private $cards;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
        $this->modifyProfils = new ArrayCollection();
        $this->accounts = new ArrayCollection();
        $this->beneficiaries = new ArrayCollection();
        $this->cards = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->uuid;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->uuid;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getPostalAddress(): ?string
    {
        return $this->postalAddress;
    }

    public function setPostalAddress(string $postalAddress): self
    {
        $this->postalAddress = $postalAddress;

        return $this;
    }

    public function getRegisterDate(): ?\DateTimeInterface
    {
        return $this->registerDate;
    }

    public function setRegisterDate(\DateTimeInterface $registerDate): self
    {
        $this->registerDate = $registerDate;

        return $this;
    }

    public function getProfilPicture(): ?string
    {
        return $this->profilPicture;
    }

    public function setProfilPicture(string $profilPicture): self
    {
        $this->profilPicture = $profilPicture;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getIdCardFront(): ?Document
    {
        return $this->IdCardFront;
    }

    public function setIdCardFront(?Document $IdCardFront): self
    {
        $this->IdCardFront = $IdCardFront;

        return $this;
    }

    public function getIdCardBack(): ?Document
    {
        return $this->IdCardBack;
    }

    public function setIdCardBack(?Document $IdCardBack): self
    {
        $this->IdCardBack = $IdCardBack;

        return $this;
    }

    public function getProofOfAddress(): ?Document
    {
        return $this->ProofOfAddress;
    }

    public function setProofOfAddress(?Document $ProofOfAddress): self
    {
        $this->ProofOfAddress = $ProofOfAddress;

        return $this;
    }

    public function getAdvisor(): ?self
    {
        return $this->advisor;
    }

    public function setAdvisor(?self $advisor): self
    {
        $this->advisor = $advisor;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(self $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
            $customer->setAdvisor($this);
        }

        return $this;
    }

    public function removeCustomer(self $customer): self
    {
        if ($this->customers->removeElement($customer)) {
            // set the owning side to null (unless already changed)
            if ($customer->getAdvisor() === $this) {
                $customer->setAdvisor(null);
            }
        }

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
            $modifyProfil->setUser($this);
        }

        return $this;
    }

    public function removeModifyProfil(ModifyProfil $modifyProfil): self
    {
        if ($this->modifyProfils->removeElement($modifyProfil)) {
            // set the owning side to null (unless already changed)
            if ($modifyProfil->getUser() === $this) {
                $modifyProfil->setUser(null);
            }
        }

        return $this;
    }

    public function getFirstMdp(): ?bool
    {
        return $this->firstMdp;
    }

    public function setFirstMdp(bool $firstMdp): self
    {
        $this->firstMdp = $firstMdp;

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
            $account->setOwner($this);
        }

        return $this;
    }

    public function removeAccount(Account $account): self
    {
        if ($this->accounts->removeElement($account)) {
            // set the owning side to null (unless already changed)
            if ($account->getOwner() === $this) {
                $account->setOwner(null);
            }
        }

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
            $beneficiary->setOwner($this);
        }

        return $this;
    }

    public function removeBeneficiary(Beneficiary $beneficiary): self
    {
        if ($this->beneficiaries->removeElement($beneficiary)) {
            // set the owning side to null (unless already changed)
            if ($beneficiary->getOwner() === $this) {
                $beneficiary->setOwner(null);
            }
        }

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
            $card->setOwner($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getOwner() === $this) {
                $card->setOwner(null);
            }
        }

        return $this;
    }
}
