<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("product:read")]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    #[Groups("product:read")]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups("product:read")]
    private ?string $shortDescription = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups("product:read")]
    private ?string $fullDescription = null;

    #[ORM\Column]
    #[Groups("product:read")]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    #[Groups("product:read")]
    private ?string $picture = null;

    /**
     * @var Collection<int, OrderHasProduct>
     */
    #[ORM\OneToMany(targetEntity: OrderHasProduct::class, mappedBy: 'product', orphanRemoval: true)]
    private Collection $order_reference;

    public function __construct()
    {
        $this->order_reference = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): static
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getFullDescription(): ?string
    {
        return $this->fullDescription;
    }

    public function setFullDescription(string $fullDescription): static
    {
        $this->fullDescription = $fullDescription;

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

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): static
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection<int, OrderHasProduct>
     */
    public function getOrderReference(): Collection
    {
        return $this->order_reference;
    }

    public function addOrderReference(OrderHasProduct $orderReference): static
    {
        if (!$this->order_reference->contains($orderReference)) {
            $this->order_reference->add($orderReference);
            $orderReference->setProduct($this);
        }

        return $this;
    }

    public function removeOrderReference(OrderHasProduct $orderReference): static
    {
        if ($this->order_reference->removeElement($orderReference)) {
            // set the owning side to null (unless already changed)
            if ($orderReference->getProduct() === $this) {
                $orderReference->setProduct(null);
            }
        }

        return $this;
    }
}
