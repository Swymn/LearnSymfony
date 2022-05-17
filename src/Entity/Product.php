<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected int $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(max: 255, maxMessage: "Votre nom doit faire maximum {{ limit }} caractères.")]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    private string $productName;

    #[ORM\Column(type: 'float')]
    #[Assert\GreaterThanOrEqual(value: 0, message: "Le prix doit être forcément supérieur à 0.")]
    #[Assert\NotBlank(message: "Le prix ne peut pas être vide.")]
    private float $price;

    #[ORM\Column(type: 'string', length: 255)]
    private string $slug;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    #[Assert\NotBlank(message: "La category ne peut pas être vide.")]
    private Category $category;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: "L'image ne peut pas être vide.")]
    private string $picture;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: "La description ne peut pas être vide.")]
    private string $shortDescription;

    public function getId(): int {
        return $this -> id;
    }

    public function getName(): string {
        return $this -> productName;
    }

    public function setName(string $productName): self {
        $this -> productName = $productName;
        return $this;
    }

    public function getPrice(): float {
        return $this -> price;
    }

    public function setPrice(float $price): self {
        $this -> price = $price;
        return $this;
    }

    public function getSlug(): string {
        return $this->slug;
    }

    public function setSlug(string $slug): self {
        $this -> slug = $slug;

        return $this;
    }

    public function getCategory(): Category {
        return $this -> category;
    }

    public function setCategory(Category $category): self {
        $this -> category = $category;

        return $this;
    }

    public function getPicture(): string {
        return $this -> picture;
    }

    public function setPicture(string $picture): self {
        $this -> picture = $picture;
        return $this;
    }

    public function getShortDescription(): string {
        return $this -> shortDescription;
    }

    public function setShortDescription(string $shortDescription): self {
        $this -> shortDescription = $shortDescription;
        return $this;
    }
}
