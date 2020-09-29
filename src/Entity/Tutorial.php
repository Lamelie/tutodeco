<?php

namespace App\Entity;

use App\Repository\TutorialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TutorialRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Tutorial
{

    //TODO: ajouter des constantes pour les statuts de validation
    const VALIDATION_PENDING = "En attente de validation";

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $picture;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $validation;

    /**
     * @ORM\OneToMany(targetEntity=Step::class, mappedBy="tutorial", orphanRemoval=true)
     */
    private $steps;

    /**
     * @ORM\OneToMany(targetEntity=UserTutorial::class, mappedBy="tutorial", orphanRemoval=true)
     */
    private $userTutorials;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="tutorial", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="tutorials")
     */
    private $tags;

    /**
     * @ORM\ManyToMany(targetEntity=Material::class, inversedBy="tutorials")
     */
    private $materials;

    /**
     * @ORM\ManyToMany(targetEntity=Tool::class)
     */
    private $tools;

    /**
     * @ORM\ManyToOne(targetEntity=Level::class, inversedBy="tutorials")
     * @ORM\JoinColumn(nullable=false)
     */
    private $level;

    /**
     * @ORM\ManyToOne(targetEntity=Cost::class, inversedBy="tutorials")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cost;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tutorials")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    public function __construct()
    {
        $this->steps = new ArrayCollection();
        $this->userTutorials = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->materials = new ArrayCollection();
        $this->tools = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getValidation(): ?string
    {
        return $this->validation;
    }

    public function setValidation(string $validation): self
    {
        $this->validation = $validation;

        return $this;
    }

    /**
     * @return Collection|Step[]
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(Step $step): self
    {
        if (!$this->steps->contains($step)) {
            $this->steps[] = $step;
            $step->setTutorial($this);
        }

        return $this;
    }

    public function removeStep(Step $step): self
    {
        if ($this->steps->contains($step)) {
            $this->steps->removeElement($step);
            // set the owning side to null (unless already changed)
            if ($step->getTutorial() === $this) {
                $step->setTutorial(null);
            }
        }

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
            $userTutorial->setTutorial($this);
        }

        return $this;
    }

    public function removeUserTutorial(UserTutorial $userTutorial): self
    {
        if ($this->userTutorials->contains($userTutorial)) {
            $this->userTutorials->removeElement($userTutorial);
            // set the owning side to null (unless already changed)
            if ($userTutorial->getTutorial() === $this) {
                $userTutorial->setTutorial(null);
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
            $comment->setTutorial($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getTutorial() === $this) {
                $comment->setTutorial(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    /**
     * @return Collection|Material[]
     */
    public function getMaterials(): Collection
    {
        return $this->materials;
    }

    public function addMaterial(Material $material): self
    {
        if (!$this->materials->contains($material)) {
            $this->materials[] = $material;
        }

        return $this;
    }

    public function removeMaterial(Material $material): self
    {
        if ($this->materials->contains($material)) {
            $this->materials->removeElement($material);
        }

        return $this;
    }

    /**
     * @return Collection|Tool[]
     */
    public function getTools(): Collection
    {
        return $this->tools;
    }

    public function addTool(Tool $tool): self
    {
        if (!$this->tools->contains($tool)) {
            $this->tools[] = $tool;
        }

        return $this;
    }

    public function removeTool(Tool $tool): self
    {
        if ($this->tools->contains($tool)) {
            $this->tools->removeElement($tool);
        }

        return $this;
    }

    public function getLevel(): ?Level
    {
        return $this->level;
    }

    public function setLevel(?Level $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getCost(): ?Cost
    {
        return $this->cost;
    }

    public function setCost(?Cost $cost): self
    {
        $this->cost = $cost;

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

    /**
     * @ORM\PrePersist()
     */
    public function prePersist() {
        $this->setCreatedAt(new \DateTime());
    }



}
