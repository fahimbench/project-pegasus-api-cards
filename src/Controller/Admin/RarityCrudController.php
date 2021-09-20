<?php

namespace App\Controller\Admin;

use App\Entity\Rarity;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RarityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Rarity::class;
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
