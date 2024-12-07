<?php

namespace App\EventListener;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;



class SlugListener
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        // Vérifiez si l'entité utilise le SlugTrait
        if (method_exists($entity, 'setSlug') && method_exists($entity, 'getRubrics')) {
            $slugify = new Slugify();
            $slug = $slugify->slugify($entity->getRubrics());
            $entity->setSlug($slug);
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        // Vérifiez si l'entité utilise le SlugTrait
        if (method_exists($entity, 'setSlug') && method_exists($entity, 'getRubrics')) {
            $slugify = new Slugify();
            $slug = $slugify->slugify($entity->getRubrics());
            $entity->setSlug($slug);
        }
    }
}
