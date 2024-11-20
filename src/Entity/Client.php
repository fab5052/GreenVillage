<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $commercial = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommercial(): ?string
    {
        return $this->commercial;
    }

    public function setCommercial(string $commercial): static
    {
        $this->commercial = $commercial;

        return $this;
    }
}
