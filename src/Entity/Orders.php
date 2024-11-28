<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use App\Entity\OrderDetails;
use App\Entity\OrderStatus;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Uid\Uuid;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;
use Gedmo\Mapping\Annotation as Gedmo;



#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;


    #[ORM\Column(length: 20)]
    private ?string $reference = null;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    #[ORM\Column(type: Types::STRING, enumType: OrderStatus::class)]
    private OrderStatus $status;

    public function __construct()
    {
        $this->status = OrderStatus::PENDING;
    }

    // Getter et Setter pour $status
    public function getStatus(): OrderStatus
    {
        return $this->status;
    }

    public function setStatus(OrderStatus $status): void
    {
        $this->status = $status;
    }
}


// enum Status: string
// {
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

// $status = Status::PENDING;

// echo $status->value; // Output: pending
// echo $status->getDescription(); // Output: La commande est en attente.

// $status = Status::SENT;

// echo $status->getDescription(); // Output: La commande a été envoyée.
// echo $status->isFinal() ? 'État final' : 'État non final'; // Output: État final

// foreach (Status::cases() as $status) {
//    echo $status->name . ' => ' . $status->getDescription() . PHP_EOL;
// }
