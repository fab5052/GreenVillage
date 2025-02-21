<?php

namespace App\Controller\Admin;

use App\Entity\InfoSuppliers;
use App\Entity\Product;
use App\Entity\Rubric;
use App\Entity\Tva;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\ProductController;
use EasyCorp\Bundle\EasyAdminBundle\Controller\RubricController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;

class ProductCrudController extends AbstractCrudController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureFields(string $pageName): iterable
    {
        {
            return [
                IdField::new('id')->hideOnForm(),
                TextField::new('label', 'Nom du produit'),
                TextEditorField::new('description', 'Description'),
                MoneyField::new('price')->setCurrency('EUR'),
                TextField::new('reference', 'Référence')
                    ->setHelp('Code unique du produit')
                    ->setRequired(true)
                    ->setMaxLength(50),
                SlugField::new('slug')->setTargetFieldName('label'),
                NumberField::new('stock')->setHelp('Quantité en stock'),      
                BooleanField::new('isAvailable', 'Disponible')
                    ->renderAsSwitch(false) 
                    ->setHelp('Indique si le produit est disponible'),
                DateTimeField::new('created_at')->setFormat('yyyy-MM-dd HH:mm:ss'),
                DateTimeField::new('updated_at')->setFormat('yyyy-MM-dd HH:mm:ss'),
                AssociationField::new('rubric')->setRequired(true),
                AssociationField::new('tva')->setRequired(true),
                AssociationField::new('infoSuppliers')->setRequired(true),
            ];
        }
}
}