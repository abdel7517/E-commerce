<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageCategoryRepository")
 */
class ImageCategory
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
     * @ORM\OneToOne(targetEntity=MainCategory::class, mappedBy="Image", cascade={"persist", "remove"})
     */
    private $mainCategory;

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

    public function getMainCategory(): ?MainCategory
    {
        return $this->mainCategory;
    }

    public function setMainCategory(MainCategory $mainCategory): self
    {
        $this->mainCategory = $mainCategory;

        // set the owning side of the relation if necessary
        if ($mainCategory->getImage() !== $this) {
            $mainCategory->setImage($this);
        }

        return $this;
    }
}
