<?php

namespace App\Controller\Admin;

use App\Entity\InfoSuppliers;
use App\Entity\Product;
use App\Entity\Rubric;
use App\Entity\Tva;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\ProductController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class ProductCrudController extends AbstractCrudController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // Récupérer les types de fournisseurs depuis la base de données
        $supplierTypes = $this->entityManager->getRepository(InfoSuppliers::class)->findAll();
        $rubric = $this->entityManager->getRepository(Rubric::class)->findAll();
        $choices = [];
        foreach ($supplierTypes as $supplier) {
            $choices[$supplier->getType()] = $supplier; // Clé = affichage, Valeur = entité
        }

        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('label', 'Nom du produit'),
            TextEditorField::new('description'),

            // Champ de sélection du type de fournisseur
            ChoiceField::new('infoSuppliers', 'Type de fournisseur')
                ->setChoices($choices)
                ->setRequired(true),

            AssociationField::new('rubric', 'Rubrique')
                ->setChoices($choices)
                ->setRequired(true),
            AssociationField::new('tva', 'TVA')->setRequired(true),
        ];
    }
}
 
