<?php

namespace App\Controller\Admin;

use App\Entity\Rubric;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class RubricCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Rubric::class;
    }

    // public function configureFields(string $pageName): iterable
    // {
    //     return [
    //         TextField::new('label', 'Nom'),
    //        // MoneyField::new('price', 'Prix')->setCurrency('EUR'),
    //     ];
    // }
}
