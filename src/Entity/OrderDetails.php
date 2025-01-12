<?php

namespace App\Entity;

use App\Repository\OrderDetailsRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use App\Entity\Orders;
use App\Enum\OrderStatus;
use App\Entity\DeliveryDetails;

use Doctrine\Common\Collections\Order;

#[ORM\Entity(repositoryClass: OrderDetailsRepository::class)]
#[ORM\Table(name: '`order_details`')]
class OrderDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: "orderDetails")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Product $product = null;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: "orderDetails")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private ?Order $order = null;

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    private ?int $quantity = null;
    private ?string $price = null;


    public function getId(): ?int
    {
        return $this->id;
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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}

    // enum OrderStatus: string
    // {s
    //     case PENDING = 'pending';   // Commande en attente
    //     case SENT = 'sent';         // Commande envoyée
    //     case REJECTED = 'rejected'; // Commande refusée
    
    //     /**
    //      * Méthode pour obtenir une description de l'état
    //      */
    //     public function getDescription(): string
    //     {
    //         return match ($this) {
    //             self::PENDING => 'La commande est en attente.',
    //             self::SENT => 'La commande a été envoyée.',
    //             self::REJECTED => 'La commande a été refusée.',
    //         };
    //     }
    
    //     /**
    //      * Méthode pour vérifier si une commande est dans un état final
    //      */
    //     public function isFinal(): bool
    //     {
    //         return match ($this) {
    //             self::SENT, self::REJECTED => true,
    //             self::PENDING => false,
    //         };
        
    //     }
    // }
    
    // $status = OrderStatus::PENDING;

    // echo $status->value; // Output: pending
    // echo $status->getDescription(); // Output: La commande est en attente.

    // $status = OrderStatus::SENT;

    // echo $status->getDescription(); // Output: La commande a été envoyée.
    // echo $status->isFinal() ? 'État final' : 'État non final'; // Output: État final

    // foreach (OrderStatus::cases() as $status) {
    //    echo $status->name . ' => ' . $status->getDescription() . PHP_EOL;
   
  

      
       

    
    


