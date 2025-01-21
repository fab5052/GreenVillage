<?php

namespace App\Enum;


enum OrderStatus: string
{
    case PENDING = 'pending';   // Commande en attente
    case SENT = 'sent';         // Commande envoyée
    case REJECTED = 'rejected'; // Commande refusée

    /**
     * Méthode pour obtenir une description de l'état
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::PENDING => 'La commande est en attente.',
            self::SENT => 'La commande a été envoyée.',
            self::REJECTED => 'La commande a été refusée.',
        };
    }

    /**
     * Méthode pour vérifier si une commande est dans un état final
     */
    public function isFinal(): bool
    {
        return match ($this) {
            self::SENT, self::REJECTED => true,
            self::PENDING => false,
        };
    
    }
}