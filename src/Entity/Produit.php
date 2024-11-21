<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $SousRubrique = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSousRubrique(): ?string
    {
        return $this->SousRubrique;
    }

    public function setSousRubrique(string $SousRubrique): static
    {
        $this->SousRubrique = $SousRubrique;

        return $this;
    }
}
