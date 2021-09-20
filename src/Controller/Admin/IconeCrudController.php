<?php

namespace App\Controller\Admin;

use App\Entity\Icone;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class IconeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Icone::class;
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
