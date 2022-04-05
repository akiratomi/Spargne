<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MeetingRequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MeetingRequestRepository::class)
 */
#[ApiResource(normalizationContext:['groups' => ['read'],'enable_max_depth'=>true],
collectionOperations:[
    "GET" => [
        'method' => 'GET', 
        'path' => '/setMeetingRequest/{desiredDate}/{customerId}/{topiId}', 
        'route_name' => 'setMeetingRequest',
        'filters' => [],
        'pagination_enabled' => false,
        'openapi_context' => [
            'summary' => "Récupère un utilisateur par son id",
            'parameters' => [
                [
                    'in' => 'path',
                    'name' => 'desiredDate',
                    'description' => 'fils de pute',
                    'required' => true,
                    'schema' => [
                        'type' => 'string'
                    ]
                ],
                [
                    'in' => 'path',
                    'name' => 'customerId',
                    'description' => 'olivier tier',
                    'required' => true,
                    'schema' => [
                        'type' => 'integer'
                    ]
                ],
                [
                    'in' => 'path',
                    'name' => 'topicId',
                    'description' => 'starfoula',
                    'required' => true,
                    'schema' => [
                        'type' => 'integer'
                    ]
                ]
            ],
        ],
    ]
]
)]
class MeetingRequest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $desired_date;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="meetingRequests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity=MeetingTopic::class, inversedBy="meetingRequests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $topic;

    public function __construct()
    {
        $this->customer = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesiredDate(): ?\DateTimeInterface
    {
        return $this->desired_date;
    }

    public function setDesiredDate(?\DateTimeInterface $desired_date): self
    {
        $this->desired_date = $desired_date;

        return $this;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(?User $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getTopic(): ?MeetingTopic
    {
        return $this->topic;
    }

    public function setTopic(?MeetingTopic $topic): self
    {
        $this->topic = $topic;

        return $this;
    }
}
