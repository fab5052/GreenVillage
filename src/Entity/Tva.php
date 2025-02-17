<?php

namespace App\Entity;

use App\Repository\TvaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TvaRepository::class)]
class Tva
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $rate = null;

    #[ORM\OneToMany(mappedBy: "tva", targetEntity: Product::class)]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRate(): ?string
    {
        return $this->rate;
    }

    public function setRate(string $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getProducts(): Collection
{
    return $this->products;
}

public function addProduct(Product $product): self
{
    if (!$this->products->contains($product)) {
        $this->products->add($product);
        $product->setTva($this);
    }
    return $this;
}

public function removeProduct(Product $product): self
{
    if ($this->products->removeElement($product)) {
        if ($product->getTva() === $this) {
            $product->setTva(null);
        }
    }
    return $this;
}

public function __toString(): string
{
    return $this->tva; 
}

}
