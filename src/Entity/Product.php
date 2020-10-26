<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="product", orphanRemoval=true, cascade = {"persist", "remove"})
     */
    private $Images;

  

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $categorie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ImageMain", mappedBy="product", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $Image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mainCategory;



    

   




    

  


    

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
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

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->Images;
    }

    public function addImage( $image): self
    {
        if (!$this->Images->contains($image)) 
        {
            $this->Images[] = $image;
            $image->setProduct($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->Images->contains($image)) {
            $this->Images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }

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

    public function getImage(): ?Imagemain
    {
        return $this->Image;
    }

    public function setImage(?Imagemain $imagemain): self
    {
        $this->Image = $imagemain;

        // set (or unset) the owning side of the relation if necessary
        $newProduct = null === $imagemain ? null : $this;
        if ($imagemain->getProduct() !== $newProduct) {
            $imagemain->setProduct($newProduct);
        }

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




   

  

    
 

   


 

   
}
