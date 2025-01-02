<?php

namespace App\Entity;

use App\Entity\Trait\SlugTrait;
use App\Repository\RubricRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use App\EventListener\SlugListener;


#[ORM\Entity(repositoryClass: RubricRepository::class)]
#[ORM\UniqueConstraint(name: 'slug', columns: ['slug'])]

class Rubric 
{
   //use SlugTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    // #[Groups(["product:read", "parent:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $label = null;

    #[ORM\Column(length: 100)]
    //#[Assert\NotBlank(message: 'Le slug ne peut pas être vide.')]
    private ?string $slug = null; 

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'rubrics', cascade: ['remove'])]    
    private ?self $parent = null;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent')]
    private Collection $rubrics;

    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'rubric')]
    private Collection $products;


    public function __construct()
    {
        $this->rubrics = new ArrayCollection();
        $this->products = new ArrayCollection();
     }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }
    
     /**
     * @return Collection<int, self>
     */
    public function getRubrics(): Collection
    {
        return $this->rubrics;
    }

    public function addRubric(self $rubric): static
    {
        if (!$this->rubrics->contains($rubric)) {
            $this->rubrics->add($rubric);
            $rubric->setParent($this);
        }

        return $this;
    }

    public function removeRubric(self $rubric): static
    {
        if ($this->rubrics->removeElement($rubric)) {
            if ($rubric->getParent() === $this) {
                $rubric->setParent(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setRubric($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            if ($product->getRubric() === $this) {
                $product->setRubric(null);
            }
        }

        return $this;
    }

}



