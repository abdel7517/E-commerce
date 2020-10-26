<?php

namespace App\Entity;

use App\Repository\MainCategoryRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MainCategoryRepository::class)
 */
class MainCategory
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
    private $name;

    /**
     * @ORM\OneToOne(targetEntity=ImageCategory::class, inversedBy="mainCategory", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Image;

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
}
