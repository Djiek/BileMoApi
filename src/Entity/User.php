<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @Groups({"customer"})
     * @Groups({"customerClient"})
     * @Groups({"user"})
     * @Groups({"userPost"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"user"})
     * @Groups({"userPost"})
     * @Groups({"customerClient"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Groups({"user"})
     * @Groups({"customerClient"})
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @Groups({"user"})
     * @Groups({"userPost"})
     * @Groups({"customerClient"})
     * @ORM\Column(type="string", length=255)
     * @Groups({"customerlist"})
     */
    private $mail;

    /**
     * @Groups({"get"})
     * @ORM\Column(type="string", length=255)
     */
    private $adress;

    /**
     * @Groups({"user"})
     * @ORM\Column(type="string", length=255)
     */
    private $dateOfBirth;

    /**
     * @Groups({"userPost"})
     * @Groups({"user"})
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="user" )
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $customer;

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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

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

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getDateOfBirth(): ?string
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(string $dateOfBirth): self
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}
