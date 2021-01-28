<?php

namespace App\Entity;

use App\Repository\CustomProjectRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CustomProjectRepository::class)
 */
class CustomProject
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $typeClient;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mess;

    /**
     * @ORM\Column(type="json")
     */
    private $img = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $day;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeClient(): ?bool
    {
        return $this->typeClient;
    }

    public function setTypeClient(bool $typeClient): self
    {
        $this->typeClient = $typeClient;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMess(): ?string
    {
        return $this->mess;
    }

    public function setMess(string $mess): self
    {
        $this->mess = $mess;

        return $this;
    }

    public function getImg(): ?array
    {
        return $this->img;
    }

    public function setImg(array $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getDay(): ?\DateTimeInterface
    {
        return $this->day;
    }

    public function setDay(\DateTimeInterface $day): self
    {
        $this->day = $day;

        return $this;
    }
}
