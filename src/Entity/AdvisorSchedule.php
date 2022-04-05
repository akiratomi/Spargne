<?php

namespace App\Entity;

use App\Repository\AdvisorScheduleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdvisorScheduleRepository::class)
 */
class AdvisorSchedule
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="advisorSchedules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $advisor;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="time")
     */
    private $duration;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="advisorSchedules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity=MeetingTopic::class, inversedBy="advisorSchedules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $topic;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdvisor(): ?User
    {
        return $this->advisor;
    }

    public function setAdvisor(?User $advisor): self
    {
        $this->advisor = $advisor;

        return $this;
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

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): self
    {
        $this->duration = $duration;

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
