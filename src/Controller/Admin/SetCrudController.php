<?php

namespace App\Controller\Admin;

use App\Entity\Card;
use App\Entity\Set;
use App\Field\ImageTo64Field;
use App\Repository\SetRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

class SetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Set::class;
    }

    public function  configureCrud(Crud $crud): Crud
    {
        return $crud->overrideTemplate('crud/index', 'admin/show/showcard.html.twig');
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response = $this->get(EntityRepository::class)->createQueryBuilder($searchDto,$entityDto,$fields,$filters);
        $response->andWhere('entity.isValid = 1');
        return $response;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('type'))
            ->add(EntityFilter::new('release_date'))
            ->add(EntityFilter::new('country'));
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            BooleanField::new('is_valid')->onlyWhenUpdating(),
            TextField::new('name'),
            TextField::new('abbreviated_name'),
            DateField::new('release_date'),
            AssociationField::new('country'),
            AssociationField::new('type'),
            IntegerField::new('cards', 'Carte validé')->formatValue(function($a){
                $t = $a->getValues();
                $n = array_filter($t, function($f){
                    return $f->getIsValid();
                });
                return count($n)."/".count($t);
            })->onlyOnIndex(),
            ImageTo64Field::new('src'),
        ];
    }

}
