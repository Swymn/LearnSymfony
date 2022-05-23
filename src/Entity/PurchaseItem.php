<?php

namespace App\Entity;

use App\Repository\PurchaseItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseItemRepository::class)]
class PurchaseItem {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'purchaseItems')]
    private Product $product;

    #[ORM\ManyToOne(targetEntity: Purchase::class, inversedBy: 'purchaseItems')]
    #[ORM\JoinColumn(nullable: false)]
    private Purchase $purchase;

    #[ORM\Column(type: 'string', length: 255)]
    private string $productName;

    #[ORM\Column(type: 'integer')]
    private int $productPrice;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    #[ORM\Column(type: 'integer')]
    private int $total;

    public function getId(): int {
        return $this -> id;
    }

    public function getProduct(): Product {
        return $this -> product;
    }

    public function setProduct(?Product $product): self {
        $this -> product = $product;

        return $this;
    }

    public function getPurchase(): Purchase {
        return $this -> purchase;
    }

    public function setPurchase(?Purchase $purchase): self {
        $this -> purchase = $purchase;

        return $this;
    }

    public function getProductName(): string {
        return $this -> productName;
    }

    public function setProductName(string $productName): self {
        $this -> productName = $productName;

        return $this;
    }

    public function getProductPrice(): int {
        return $this -> productPrice;
    }

    public function setProductPrice(int $productPrice): self {
        $this -> productPrice = $productPrice;

        return $this;
    }

    public function getQuantity(): int {
        return $this -> quantity;
    }

    public function setQuantity(int $quantity): self {
        $this -> quantity = $quantity;

        return $this;
    }

    public function getTotal(): int {
        return $this -> total;
    }

    public function setTotal(int $total): self {
        $this -> total = $total;

        return $this;
    }
}
