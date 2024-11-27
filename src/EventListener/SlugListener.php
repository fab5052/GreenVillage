<?php

namespace App\EventListener;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Event\LifecycleEventArgs;

class SlugListener
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        // Vérifiez si l'entité utilise le SlugTrait
        if (method_exists($entity, 'setSlug') && method_exists($entity, 'getRubrique')) {
            $slugify = new Slugify();
            $slug = $slugify->slugify($entity->getNom());
            $entity->setSlug($slug);
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        // Vérifiez si l'entité utilise le SlugTrait
        if (method_exists($entity, 'setSlug') && method_exists($entity, 'get) {
            $slugify = new Slugify();
            $slug = $slugify->slugify($entity->getImage());
            $entity->setSlug($slug);
        }
    }
}
