<?php

namespace App\Entity;

use App\Entity\Trait\SlugTrait;
use App\Entity\Trait\CreatedAtTrait;
use App\Entity\InfoSuppliers;
use App\Entity\Rubric;
use App\Assert\GreaterThan;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use DateTime;



#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\UniqueConstraint(name: 'slug', columns: ['slug'])]
#[ORM\UniqueConstraint(name: 'reference', columns: ['reference'])]
class Product
{
    //use CreatedAtTrait;
    //use SlugTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['product:read'])]
    private ?string $label = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['product:read'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['product:read'])]
    private ?string $price = null;

    #[ORM\Column(length: 50)]
    private ?string $reference = null;

    #[ORM\Column(type: 'string', length: '100', unique: true)]
    #[Assert\NotBlank(message: 'Le slug ne peut pas être vide.')]
    #[Assert\Unique]

    private ?string $slug = null;

    
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?InfoSuppliers $suppliers = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Rubric $rubric = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $weight = null;

    #[ORM\ManyToOne(targetEntity: Tva::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tva $tva = null;

        /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'product')]
    private Collection $image;

    #[ORM\OneToMany(mappedBy: "product", targetEntity: DeliveryDetails::class)]
    private Collection $deliveryDetails;

    public function __construct()
    {
        $this->image = new ArrayCollection();
        $this->deliveryDetails = new ArrayCollection();

    }


    #[Assert\GreaterThan(
        value: 0,
        message: "Le produit est indisponible."
    )]

    #[ORM\Column(type: 'integer')]
    private int $stock;

    #[ORM\Column(type: 'boolean')]
    private bool $isAvailable = true;

    public function setStock(int $stock): self
    {
    $this->stock = $stock;
    $this->isAvailable = $stock > 0;

    return $this;
    }

    public function isAvailable(): bool
    {
        return $this->stock > 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function label(): ?string
    {
        return $this->label;
    }

    public function setlabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    // public function getViewRubrics(): ?string
    // {
    //     return $this->viewRubrics;
    // }

    // public function viewRubric(string $viewRubric): static
    // {
    //     $this->viewRubrics = $viewRubric;

    //     return $this;
    // }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }


public function getCreatedAt(): ?\DateTimeImmutable
{
    return $this->createdAt;
}

public function setCreatedAt(\DateTimeImmutable $createdAt): static
{
    $this->createdAt = $createdAt;

    return $this;
}

public function getUpdatedAt(): ?\DateTimeImmutable
{
    return $this->updatedAt;
}

public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
{
    $this->updatedAt = $updatedAt;

    return $this;
}

public function getInfoSuppliers(): ?InfoSuppliers
{
    return $this->suppliers;
}

public function setInfoSupplier(?InfoSuppliers $infoSuppliers): static
{
    $this->suppliers = $infoSuppliers;

    return $this;
}

/**
 * @return Collection<int, Image>
 */
public function getImage(): Collection
{
    return $this->image;
}
public function addImage(Image $image): static
{
    if (!$this->image->contains($image)) {
        $this->image->add($image);
        $image->setProduct($this);
    }

    return $this;
}

public function removeImage(Image $image): static
{
    if ($this->image->removeElement($image)) {
        if ($image->getProduct() === $this) {
            $image->setProduct($this);
        }
    }

    return $this;
}

public function getRubric(): ?Rubric
{
    return $this->rubric;
}

public function setRubric( ?Rubric $rubric): static
{
    $this->rubric = $rubric;

    return $this;
}

public function getWeight(): ?string
{
    return $this->weight;
}

public function setWeight(string $weight): static
{
    $this->weight = $weight;

    return $this;
}

public function getTva(): ?Tva
{
    return $this->tva;
}

public function setTva(?Tva $tva): static
{
    $this->tva = $tva;

    return $this;
}

/**
 * @return Collection<int, DeliveryDetails>
 */
public function getDeliveryDetails(): Collection
{
    return $this->deliveryDetails;
}

public function addDeliveryDetail(DeliveryDetails $deliveryDetail): self
{
    if (!$this->deliveryDetails->contains($deliveryDetail)) {
        $this->deliveryDetails->add($deliveryDetail);
        $deliveryDetail->setProduct($this);
    }

    return $this;
}

public function removeDeliveryDetail(DeliveryDetails $deliveryDetail): self
{
    if ($this->deliveryDetails->removeElement($deliveryDetail)) {
        if ($deliveryDetail->getProduct() === $this) {
            $deliveryDetail->setProduct(null);
        }
    }

    return $this;
}
}

