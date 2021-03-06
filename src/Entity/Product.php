<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Constraints as Assert; 

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @Groups({"productList"})
     * @Groups({"product"})
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @Groups({"productList"})
     * @Groups({"product"})
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\NotBlank
     * @Groups({"product"})
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @Groups({"product"})
     * @ORM\Column(type="date")
     */
    private $dateCreation;

    /**
     * @Assert\NotBlank
     * @Groups({"product"})
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @Assert\NotBlank
     * @Groups({"product"})
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @OA\Property(type="string")
     * @Groups({"productList"})
     * @Groups({"product"})
     */
    private $link;

    public function getLink()
    {
        return "/api/products/" . $this->id;
    }

    /**
     * Product constructor
     */
    public function __construct()
    {
        $this->dateCreation = new DateTimeImmutable();
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get the value of dateCreation
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set the value of dateCreation
     *
     * @return  self
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }
}
