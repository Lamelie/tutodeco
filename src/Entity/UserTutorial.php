<?php

namespace App\Entity;

use App\Repository\UserTutorialRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserTutorialRepository::class)
 */
class UserTutorial
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $rate;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $todo;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $done;

    /**
     * @ORM\ManyToOne(targetEntity=Tutorial::class, inversedBy="userTutorials")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tutorial;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userTutorials")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(?int $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    public function getTodo(): ?bool
    {
        return $this->todo;
    }

    public function setTodo(?bool $todo): self
    {
        $this->todo = $todo;

        return $this;
    }

    public function getDone(): ?bool
    {
        return $this->done;
    }

    public function setDone(?bool $done): self
    {
        $this->done = $done;

        return $this;
    }

    public function getTutorial(): ?Tutorial
    {
        return $this->tutorial;
    }

    public function setTutorial(?Tutorial $tutorial): self
    {
        $this->tutorial = $tutorial;

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
