<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ImageCategory", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mainCategory;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="array")
     */
    private $filtre = [];

  

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

    public function getImage(): ?ImageCategory
    {
        return $this->Image;
    }

    public function setImage(ImageCategory $Image): self
    {
        $this->Image = $Image;

        return $this;
    }

    public function getMainCategory(): ?string
    {
        return $this->mainCategory;
    }

    public function setMainCategory(string $mainCategory): self
    {
        $this->mainCategory = $mainCategory;

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

    public function getFiltre(): ?array
    {
        return $this->filtre;
    }

    public function setFiltre(array $filtre): self
    {
        $this->filtre = $filtre;

        return $this;
    }

  
}
