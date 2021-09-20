<?php

namespace App\Controller\Admin;

use App\Entity\Card;
use App\Field\ImageTo64Field;
use App\Form\ImageTo64FormType;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

class MonsterCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Card::class;
    }


    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $response->andWhere('entity.isMonster = 1');
        $response->andWhere('entity.isValid = 1');

        return $response;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(NumericFilter::new('attack', 'Attaque'))
            ->add(NumericFilter::new('defense', 'Défense'))
            ->add(NumericFilter::new('level', 'Niveau/Rank'))
            ->add(NumericFilter::new('pendulumScale', 'Nv. Pendule'))
            ->add(NumericFilter::new('linkLevel', 'Nv. Lien'))
            ->add(EntityFilter::new('attribute', 'Attribut'))
            ->add(EntityFilter::new('typeMonster', 'Type Monstre'))
            ->add(EntityFilter::new('cardType', 'Type Carte'))
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
        $card->setIsMonster(true);

        return $card;
    }

    public function configureActions(Actions $actions): Actions
    {
        $create = Action::new('create', 'Créer une carte Monstre', null)
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
            IntegerField::new('attack', 'Attaque'),
            IntegerField::new('defense', 'Défense'),
            TextareaField::new('description', 'Description')->setTemplatePath('admin/fields/descriptionmini.html.twig'),
            TextField::new('id_card', 'ID'),
            IntegerField::new('level', 'Niveau/Rank'),
            IntegerField::new('pendulum_scale', 'Nv. Pendule'),
            TextareaField::new('pendulum_desc', 'Desc. Pendule')->setTemplatePath('admin/fields/descriptionmini.html.twig'),
            IntegerField::new('link_level', 'Nv. Lien'),
            AssociationField::new('attribute', 'Attribut'),
            AssociationField::new('typeMonster', 'Type Monstre')->setTemplatePath('admin/fields/typemonster.html.twig'),
            AssociationField::new('cardType', 'Type Carte')->setTemplatePath('admin/fields/typecard.html.twig'),
            AssociationField::new('rarity', 'Rareté'),
            AssociationField::new('relatedSet', 'Set'),
            ImageTo64Field::new('src'),
        ];
    }

}
