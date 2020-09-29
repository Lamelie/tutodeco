<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\AsciiSlugger as Slugger;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
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
    private $firstName;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nickname;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pictureName;

    /**
     * @ORM\OneToMany(targetEntity=UserTutorial::class, mappedBy="user", orphanRemoval=true)
     */
    private $userTutorials;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="userTos")
     */
    private $userFroms;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="userFroms")
     */
    private $userTos;

    /**
     * @ORM\OneToMany(targetEntity=Tutorial::class, mappedBy="user")
     */
    private $tutorials;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    public function __construct()
    {
        $this->userTutorials = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->userFroms = new ArrayCollection();
        $this->userTos = new ArrayCollection();
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): self
    {
        $this->nickname = $nickname;

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
        return $this->pictureName;
    }

    public function setPictureName(?string $pictureName): self
    {
        $this->pictureName = $pictureName;

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
        return $this->userFroms;
    }

    public function addUserFrom(self $userFrom): self
    {
        if (!$this->userFroms->contains($userFrom)) {
            $this->userFroms[] = $userFrom;
        }

        return $this;
    }

    public function removeUserFrom(self $userFrom): self
    {
        if ($this->userFroms->contains($userFrom)) {
            $this->userFroms->removeElement($userFrom);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getUserTos(): Collection
    {
        return $this->userTos;
    }

    public function addUserTo(self $userTo): self
    {
        if (!$this->userTos->contains($userTo)) {
            $this->userTos[] = $userTo;
            $userTo->addUserFrom($this);
        }

        return $this;
    }

    public function removeUserTo(self $userTo): self
    {
        if ($this->userTos->contains($userTo)) {
            $this->userTos->removeElement($userTo);
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

    public function __toString()
    {
        return $this->getFirstname() . " " . $this->getLastname();
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $firstname, $lastname, $nickname): self
    {
        $slug = new Slugger();
        $slug->slug($firstname, $lastname, $nickname);
        $this->slug = $slug;

        return $this;
    }

}
