<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
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
     * @ORM\Column(type="array")
     */
    private $description = [];

    /**
     * @ORM\Column(type="float")
     */
    private $price;


   /**
     * @ORM\Column(type="string")
     */
    private $imageMain;
  

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $categorie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;


     /**
     * @ORM\Column(type="array")
     */
    private $complementaryImage = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mainCategory;

    /**
     * @ORM\Column(type="boolean")
     */
    private $PriceML=true;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $PriceOfML;

     /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $avaibility;

 

    

    public function __construct()
    {
   
        $this->Images = new ArrayCollection();
    }

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

    public function getDescription(): ?array
    {
        return $this->description;
    }

    public function setDescription(array $description): self
    {
        $this->description = $description;

        return $this;
    }



    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getImageMain(): ?string
    {
        return $this->imageMain;
    }

    public function setImageMain(string $imageMain): self
    {
        $this->imageMain = $imageMain;

        return $this;
    }

    

    
    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getComplementaryImage(): ?array
    {
        return $this->complementaryImage;
    }

    public function setComplementaryImage(array $complementaryImage): self
    {
        $this->complementaryImage = $complementaryImage;

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

    public function getPriceML(): ?bool
    {
        return $this->PriceML;
    }

    public function setPriceML(bool $PriceML): self
    {
        $this->PriceML = $PriceML;

        return $this;
    }

    public function getPriceOfML(): ?string
    {
        return $this->PriceOfML;
    }

    public function setPriceOfML(?string $PriceOfML): self
    {
        $this->PriceOfML = $PriceOfML;

        return $this;
    }

    public function getAvaibility(): ?int
    {
        return $this->avaibility;
    }

    public function setAvaibility(?int $avaibility): self
    {
        $this->avaibility = $avaibility;

        return $this;
    }



}
