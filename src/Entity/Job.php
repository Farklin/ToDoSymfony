<?php

namespace App\Entity;

use App\Repository\JobRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=JobRepository::class)
 */
class Job
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_finish;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sorting;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="jobs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDateFinish(): ?\DateTimeInterface
    {
        return $this->date_finish;
    }

    public function setDateFinish(?\DateTimeInterface $date_finish): self
    {
        $this->date_finish = $date_finish;

        return $this;
    }

    public function getSorting(): ?int
    {
        return $this->sorting;
    }

    public function setSorting(?int $sorting): self
    {
        $this->sorting = $sorting;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
