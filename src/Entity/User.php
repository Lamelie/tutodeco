<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
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
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $username;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $picture_name;

    /**
     * @ORM\OneToMany(targetEntity=UserTutorial::class, mappedBy="user", orphanRemoval=true)
     */
    private $userTutorials;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="user_tos")
     */
    private $user_froms;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="user_from")
     */
    private $user_tos;

    /**
     * @ORM\OneToMany(targetEntity=Tutorial::class, mappedBy="user")
     */
    private $tutorials;

    public function __construct()
    {
        $this->userTutorials = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->user_froms = new ArrayCollection();
        $this->user_tos = new ArrayCollection();
        $this->tutorials = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPictureName(): ?string
    {
        return $this->picture_name;
    }

    public function setPictureName(?string $picture_name): self
    {
        $this->picture_name = $picture_name;

        return $this;
    }

    /**
     * @return Collection|UserTutorial[]
     */
    public function getUserTutorials(): Collection
    {
        return $this->userTutorials;
    }

    public function addUserTutorial(UserTutorial $userTutorial): self
    {
        if (!$this->userTutorials->contains($userTutorial)) {
            $this->userTutorials[] = $userTutorial;
            $userTutorial->setUser($this);
        }

        return $this;
    }

    public function removeUserTutorial(UserTutorial $userTutorial): self
    {
        if ($this->userTutorials->contains($userTutorial)) {
            $this->userTutorials->removeElement($userTutorial);
            // set the owning side to null (unless already changed)
            if ($userTutorial->getUser() === $this) {
                $userTutorial->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getUserFroms(): Collection
    {
        return $this->user_froms;
    }

    public function addUserFrom(self $userFrom): self
    {
        if (!$this->user_froms->contains($userFrom)) {
            $this->user_froms[] = $userFrom;
        }

        return $this;
    }

    public function removeUserFrom(self $userFrom): self
    {
        if ($this->user_froms->contains($userFrom)) {
            $this->user_froms->removeElement($userFrom);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getUserTos(): Collection
    {
        return $this->user_tos;
    }

    public function addUserTo(self $userTo): self
    {
        if (!$this->user_tos->contains($userTo)) {
            $this->user_tos[] = $userTo;
            $userTo->addUserFrom($this);
        }

        return $this;
    }

    public function removeUserTo(self $userTo): self
    {
        if ($this->user_tos->contains($userTo)) {
            $this->user_tos->removeElement($userTo);
            $userTo->removeUserFrom($this);
        }

        return $this;
    }

    /**
     * @return Collection|Tutorial[]
     */
    public function getTutorials(): Collection
    {
        return $this->tutorials;
    }

    public function addTutorial(Tutorial $tutorial): self
    {
        if (!$this->tutorials->contains($tutorial)) {
            $this->tutorials[] = $tutorial;
            $tutorial->setUser($this);
        }

        return $this;
    }

    public function removeTutorial(Tutorial $tutorial): self
    {
        if ($this->tutorials->contains($tutorial)) {
            $this->tutorials->removeElement($tutorial);
            // set the owning side to null (unless already changed)
            if ($tutorial->getUser() === $this) {
                $tutorial->setUser(null);
            }
        }

        return $this;
    }
}
