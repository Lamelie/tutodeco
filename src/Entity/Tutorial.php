<?php

namespace App\Entity;

use App\Repository\TutorialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=TutorialRepository::class)
 * @Vich\Uploadable
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
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="tutorial_image", fileNameProperty="imageName", size="imageSize")
     *
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string")
     *
     * @var string|null
     */
    private $imageName;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int|null
     */
    private $imageSize;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

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
     * @ORM\OneToMany(targetEntity=Step::class, mappedBy="tutorial", orphanRemoval=true, cascade={"persist"})
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

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime("now");
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
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

    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTime("now");

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
     * Fonction pour récupérer le nombre de "done" pour chaque tutoriel
     * @return int
     */
    public function getNbDone(): int
    {
        return array_reduce($this->getUserTutorials()->toArray(), function($carry, $userTutorial) {
            /** @var UserTutorial $userTutorial */
            return $carry + ($userTutorial->getDone() ? 1 : 0);
        }, 0);
    }

    /**
     * Fonction pour récupérer le nombre de "todo" pour chaque tutoriel
     * @return int
     */
    public function getNbTodo(): int
    {
        return array_reduce($this->getUserTutorials()->toArray(), function($carry, $userTutorial) {
            /** @var UserTutorial $userTutorial */
            return $carry + ($userTutorial->getTodo() ? 1 : 0);
        }, 0);
    }

    /**
     *
     * Permet de savoir si le tutoriel a été fait par l'utilisateur ou pas
     *
     * @param User $user
     * @return boolean
     */
    public function isDoneByUser (User $user) : bool
    {
        $userTutorials = $this->getUserTutorials();
        foreach ($userTutorials as $userTutorial) {
            if ($userTutorial->getUser() === $user and $userTutorial->getDone() == 1) {
                return true;
            }
        } return false;
    }

    /**
     *
     * Permet de savoir si le tutoriel a été fait par l'utilisateur ou pas
     *
     * @param User $user
     * @return boolean
     */
    public function isTodoByUser (User $user) : bool
    {
        $userTutorials = $this->getUserTutorials();
        foreach ($userTutorials as $userTutorial) {
            if ($userTutorial->getUser() === $user and $userTutorial->getTodo() == 1) {
                return true;
            }
        } return false;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist() {
        $this->setCreatedAt(new \DateTime());
    }

    public function __toString()
    {
        return $this->getTitle();
    }


}
