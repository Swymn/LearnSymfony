<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
class Purchase {

    public const STATUS_PENDING = 'PENDING';
    public const STATUS_PAID = 'PAID';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $fullName;

    #[ORM\Column(type: 'string', length: 255)]
    private string $address;

    #[ORM\Column(type: 'string', length: 255)]
    private string $postalCode;

    #[ORM\Column(type: 'string', length: 255)]
    private string $city;

    #[ORM\Column(type: 'integer')]
    private int $total;

    #[ORM\Column(type: 'string', length: 255)]
    private string $status = Purchase::STATUS_PENDING;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'purchases')]
    private User $user;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $purchasedAt;

    #[ORM\OneToMany(mappedBy: 'purchase', targetEntity: PurchaseItem::class, orphanRemoval: true)]
    private Collection $purchaseItems;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $StripeIntentID;

    public function __construct() {
        $this->purchaseItems = new ArrayCollection();
    }

    public function getId(): int {
        return $this -> id;
    }

    public function getFullName(): string {
        return $this -> fullName;
    }

    public function setFullName(string $fullName): self {
        $this -> fullName = $fullName;

        return $this;
    }

    public function getAddress(): string {
        return $this -> address;
    }

    public function setAddress(string $address): self {
        $this -> address = $address;

        return $this;
    }

    public function getPostalCode(): string {
        return $this -> postalCode;
    }

    public function setPostalCode(string $postalCode): self {
        $this -> postalCode = $postalCode;

        return $this;
    }

    public function getCity(): ?string {
        return $this -> city;
    }

    public function setCity(string $city): self {
        $this -> city = $city;

        return $this;
    }

    public function getTotal(): int {
        return $this -> total;
    }

    public function setTotal(int $total): self {
        $this -> total = $total;

        return $this;
    }

    public function getStatus(): string {
        return $this -> status;
    }

    public function setStatus(string $status): self {
        $this -> status = $status;

        return $this;
    }

    public function getUser(): User {
        return $this -> user;
    }

    public function setUser(User|UserInterface $user): self {
        $this -> user = $user;

        return $this;
    }

    public function getPurchasedAt(): \DateTimeInterface {
        return $this -> purchasedAt;
    }

    public function setPurchasedAt(\DateTimeInterface $purchasedAt): self {
        $this -> purchasedAt = $purchasedAt;

        return $this;
    }

    /**
     * @return Collection<int, PurchaseItem>
     */
    public function getPurchaseItems(): Collection {
        return $this -> purchaseItems;
    }

    public function addPurchaseItem(PurchaseItem $purchaseItem): self {
        if (!$this -> purchaseItems -> contains($purchaseItem)) {
            $this -> purchaseItems[] = $purchaseItem;
            $purchaseItem -> setPurchase($this);
        }

        return $this;
    }

    public function removePurchaseItem(PurchaseItem $purchaseItem): self {
        if ($this -> purchaseItems -> removeElement($purchaseItem)) {
            // set the owning side to null (unless already changed)
            if ($purchaseItem -> getPurchase() === $this) {
                $purchaseItem -> setPurchase(null);
            }
        }

        return $this;
    }

    public function getStripeIntentID(): string {
        return $this -> StripeIntentID;
    }

    public function setStripeIntentID(string $StripeIntentID): self {
        $this -> StripeIntentID = $StripeIntentID;

        return $this;
    }
}