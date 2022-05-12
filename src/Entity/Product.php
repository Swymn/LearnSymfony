<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'integer')]
    private string $price;

    #[ORM\Column(type: 'string', length: 255)]
    private string $slug;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'products')]
    private Category $category;

    #[ORM\Column(type: 'string', length: 255)]
    private string $picture;

    #[ORM\Column(type: 'text')]
    private string $shortDescription;

    public function getId(): int {
        return $this -> id;
    }

    public function getName(): string {
        return $this -> name;
    }

    public function setName(string $name): self {
        $this -> name = $name;
        return $this;
    }

    public function getPrice(): int {
        return $this -> price;
    }

    public function setPrice(int $price): self {
        $this -> price = $price;
        return $this;
    }

    public function convertPrice(): int {

        return $this -> price / 100;

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
