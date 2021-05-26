<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use DateTimeImmutable;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 */
class Customer
{
    /**
     * @Groups({"customer"})
     * @Groups({"user"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups({"customer"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Groups({"customer"})
     * @ORM\Column(type="date")
     */
    private $creationDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @Groups({"customer"})
    * @ORM\Column(type="string", length=255)
     */
    private $numberSiret;

    /**
     * @Groups({"customer"})
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="customer", orphanRemoval=true)
     */
    private $user;

    /**
     * @Groups({"customer"})
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->creationDate = new DateTimeImmutable();
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

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection|user[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(user $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setCustomer($this);
        }

        return $this;
    }

    public function removeUser(user $user): self
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCustomer() === $this) {
                $user->setCustomer(null);
            }
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of numberSiret
     */ 
    public function getNumberSiret()
    {
        return $this->numberSiret;
    }

    /**
     * Set the value of numberSiret
     *
     * @return  self
     */ 
    public function setNumberSiret($numberSiret)
    {
        $this->numberSiret = $numberSiret;

        return $this;
    }
}
