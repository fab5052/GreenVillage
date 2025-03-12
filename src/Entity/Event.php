<?php

namespace App\Entity;
 
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\EventRepository;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/event',
            normalizationContext: ['groups' => ['event:read']]
        ),
        new Post(
            uriTemplate: '/event',
            denormalizationContext: ['groups' => ['event:write']],
            normalizationContext: ['groups' => ['event:read']]
        ),
        new Get(
            uriTemplate: '/event/{id}',
            normalizationContext: ['groups' => ['event:read']]
        ),
        new Put(
            uriTemplate: '/event/{id}',
            denormalizationContext: ['groups' => ['event:write']],
            normalizationContext: ['groups' => ['event:read']]
        ),
        new Delete(
            uriTemplate: '/event/{id}'
        )
    ]
)]
#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['event:read', 'event:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['event:read', 'event:write'])]
    private ?string $title = null;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['event:read', 'event:write'])]
    private ?\DateTimeInterface $start = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['event:read', 'event:write'])]
    private ?\DateTimeInterface $end = null;

    #[ORM\Column(length: 10, nullable: true)]
    #[Groups(['event:read', 'event:write'])]
    private ?string $color = null;

    public function getId(): ?int { return $this->id; }

    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): static { $this->title = $title; return $this; }

    public function getStart(): ?\DateTimeInterface { return $this->start; }
    public function setStart(\DateTimeInterface $start): static { $this->start = $start; return $this; }

    public function getEnd(): ?\DateTimeInterface { return $this->end; }
    public function setEnd(?\DateTimeInterface $end): static { $this->end = $end; return $this; }

    public function getColor(): ?string { return $this->color; }
    public function setColor(?string $color): static { $this->color = $color; return $this; }
}
