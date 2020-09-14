<?php

namespace App\Controller\Admin;

use App\Entity\ProductPerso;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Controller\CrudControllerInterface;

class ProductPersoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductPerso::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
