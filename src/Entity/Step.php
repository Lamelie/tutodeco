<?php

namespace App\Entity;

use App\Repository\StepRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=StepRepository::class)
 * @Vich\Uploadable
 */
class Step
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
    private $number;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="tutorial_image", fileNameProperty="pictureName", size="imageSize")
     *
     * @var File|null
     *
     * @Assert\Image(
     *     minWidth = 320,
     *     minWidthMessage = "La largeur de l'image est insuffisante ({{ width }}px). La taille minimum requise est de {{ min_width }}px",
     *     minHeight = 240,
     *     minHeightMessage="La hauteur de l'image est insuffisante ({{ height }}px). La taille minimum requise est de {{ min_height }}px",
     *     mimeTypes = {"image/jpeg", "image/png","image/jpg", "image/gif"},
     *     mimeTypesMessage = "Seules les images en .jpeg .png .jpg et .gif sont acceptées"
     *
     * )
     */

    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null
     */
    private $pictureName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var int|null
     */
    private $imageSize;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $video_url;

    /**
     * @ORM\ManyToOne(targetEntity=Tutorial::class, inversedBy="steps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $tutorial;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var DateTimeInterface|null
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): self
    {
        $this->number = $number;

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

    public function getVideoUrl(): ?string
    {
        return $this->video_url;
    }

    public function setVideoUrl(?string $video_url): self
    {
        $this->video_url = $video_url;

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

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function setUpdatedAt(): self
    {
        $this->updatedAt = new \DateTime("now");

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }


    public function __toString()
    {
        return $this->getDescription();
    }
}
