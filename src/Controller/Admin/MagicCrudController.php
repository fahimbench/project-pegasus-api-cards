<?php

namespace App\Controller\Admin;

use App\Entity\Card;
use App\Field\ImageTo64Field;
use App\Repository\CardRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

class MagicCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Card::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        $response = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $response->andWhere('entity.isMagic = 1');
        $response->andWhere('entity.isValid = 1');

        return $response;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('icone', 'Type Magie/Piège'))
            ->add(EntityFilter::new('rarity', 'Rareté'))
            ->add(EntityFilter::new('relatedSet', 'Set'));

    }


    public function  configureCrud(Crud $crud): Crud
    {
        return $crud->overrideTemplate('crud/index', 'admin/show/showcard.html.twig');
    }

    public function createEntity(string $entityFqcn)
    {
        $card = new Card();
        $card->setIsMagic(true);

        return $card;
    }

    public function configureActions(Actions $actions): Actions
    {
        $create = Action::new('create', 'Créer une carte Magie', null)
            ->createAsGlobalAction()
            ->linkToCrudAction(Action::NEW)
            ->addCssClass('btn btn-primary');

        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->add(Crud::PAGE_INDEX, $create);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            BooleanField::new('is_valid', 'Validé')->onlyWhenUpdating(),
            TextField::new('name', 'Nom'),
            TextareaField::new('description', 'Description')->setTemplatePath('admin/fields/descriptionmini.html.twig'),
            TextField::new('id_card', 'ID'),
            AssociationField::new('icone', 'Type Magie/Piège'),
            AssociationField::new('rarity', 'Rareté'),
            AssociationField::new('relatedSet', 'Set'),
            ImageTo64Field::new('src'),
        ];
    }

}
