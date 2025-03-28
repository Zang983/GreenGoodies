<?php

namespace App\Entity;

use App\Repository\OrderHasProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderHasProductRepository::class)]
class OrderHasProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false,onDelete: 'CASCADE', referencedColumnName: 'id')]
    private ?Order $order_reference = null;

    #[ORM\ManyToOne(inversedBy: 'order_reference', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, referencedColumnName: 'id',)]
    private ?Product $product = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getOrderReference(): ?Order
    {
        return $this->order_reference;
    }

    public function setOrderReference(?Order $order_reference): static
    {
        $this->order_reference = $order_reference;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }
}
