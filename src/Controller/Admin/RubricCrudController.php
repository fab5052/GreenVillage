<?php

namespace App\Controller\Admin;

use App\Entity\Rubric;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class RubricCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Rubric::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('label', 'Nom du produit'),
            SlugField::new('slug')->setTargetFieldName('label'),
            TextEditorField::new('description', 'Description'),
            DateTimeField::new('created_at')->setFormat('yyyy-MM-dd HH:mm:ss'),
            AssociationField::new('parent')->setRequired(true),
        ];
    }
}
