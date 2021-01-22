<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="array")
     */
    private $cart = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $numberAdress;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nameAdress;

    /**
     * @ORM\Column(type="integer")
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="Orders")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Expedition;

    /**
     * @ORM\Column(type="integer")
     */
    private $state;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $orderCode;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $ready;

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCart(): ?array
    {
        return $this->cart;
    }

    public function setCart(array $cart): self
    {
        $this->cart = $cart;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNumberAdress(): ?int
    {
        return $this->numberAdress;
    }

    public function setNumberAdress(int $numberAdress): self
    {
        $this->numberAdress = $numberAdress;

        return $this;
    }

    public function getNameAdress(): ?string
    {
        return $this->nameAdress;
    }

    public function setNameAdress(string $nameAdress): self
    {
        $this->nameAdress = $nameAdress;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->postalCode;
    }

    public function setPostalCode(int $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getExpedition(): ?string
    {
        return $this->Expedition;
    }

    public function setExpedition(string $Expedition): self
    {
        $this->Expedition = $Expedition;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getOrderCode(): ?string
    {
        return $this->orderCode;
    }

    public function setOrderCode(string $orderCode): self
    {
        $this->orderCode = $orderCode;

        return $this;
    }

    public function getReady(): ?bool
    {
        return $this->ready;
    }

    public function setReady(?bool $ready): self
    {
        $this->ready = $ready;

        return $this;
    }

}
