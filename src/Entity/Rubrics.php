<?php

namespace App\Entity;

use App\Entity\Trait\SlugTrait;
use App\Repository\RubricsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: RubricsRepository::class)]
class Rubrics 
{
    use SlugTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    //#[Groups(["read:products", "read:parent"])]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'subRubrics')]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', onDelete: 'SET NULL', nullable: true)]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class, cascade: ['persist', 'remove'])]
    private Collection $subRubrics;


    public function __construct()
    {
        $this->subRubrics = new ArrayCollection();
//         $this->product = new ArrayCollection();
     }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, Rubrics>
     */
    public function getSubRubrics(): Collection
    {
        return $this->subRubrics;
    }

    public function addSubRubric(self $subRubric): self
    {
        if (!$this->subRubrics->contains($subRubric)) {
            $this->subRubrics->add($subRubric);
            $subRubric->setParent($this);
        }

        return $this;
    }

    public function removeSubRubric(self $subRubric): self
    {
        if ($this->subRubrics->removeElement($subRubric)) {
            if ($subRubric->getParent() === $this) {
                $subRubric->setParent(null);
            }
        }

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
    
    //  /**
    //  * @return Collection<int, self>
    //  */
    // public function getRubrics(): Collection
    // {
    //     return $this->rubric;
    // }

    // public function addRubric(self $rubrics): static
    // {
    //     if (!$this->rubric->contains($rubrics)) {
    //         $this->rubric->add($rubrics);
    //         $rubrics->setParent($this);
    //     }

    //     return $this;
    // }

    // public function removeRubric(self $rubric): static
    // {
    //     if ($this->rubric->removeElement($rubric)) {
    //         // set the owning side to null (unless already changed)
    //         if ($rubric->getParent() === $this) {
    //             $rubric->setParent(null);
    //         }
    //     }

    //     return $this;
    // }

    // /**
    //  * @return Collection<int, Products>
    //  */
    // public function getProducts(): Collection
    // {
    //     return $this->product;
    // }

    // public function addProduct(Products $product): static
    // {
    //     if (!$this->products->contains($product)) {
    //         $this->products->add($product);
    //         $product->setRubric($this);
    //     }

    //     return $this;
    // }

    // public function removeProduct(Products $product): static
    // {
    //     if ($this->product->removeElement($product)) {
    //         // set the owning side to null (unless already changed)
    //         if ($product->getRubrics() === $this) {
    //             $product->setRubric(null);
    //         }
    //     }

    //     return $this;
    // }


}



