<?php

namespace App\Controller\Admin;

use App\Entity\Card;
use App\Field\ImageTo64Field;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

class CardCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return Card::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $response = $this->get(EntityRepository::class)->createQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $response->andWhere('entity.isValid = 1');


        return $response;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(BooleanFilter::new('isMonster','Monstre'))
            ->add(BooleanFilter::new('isTrap', 'Piège'))
            ->add(BooleanFilter::new('isMagic', 'Magie'))
            ->add(NumericFilter::new('attack', 'Attaque'))
            ->add(NumericFilter::new('defense', 'Défense'))
            ->add(NumericFilter::new('level', 'Niveau/Rank'))
            ->add(NumericFilter::new('pendulumScale', 'Nv. Pendule'))
            ->add(NumericFilter::new('linkLevel', 'Nv. Lien'))
            ->add(EntityFilter::new('attribute', 'Attribut'))
            ->add(EntityFilter::new('typeMonster', 'Type Monstre'))
            ->add(EntityFilter::new('cardType', 'Type Carte'))
            ->add(EntityFilter::new('icone', 'Type Magie/Piège'))
            ->add(EntityFilter::new('rarity', 'Rareté'))
            ->add(EntityFilter::new('relatedSet', 'Set'));
    }

    public function  configureCrud(Crud $crud): Crud
    {
        return $crud->overrideTemplate('crud/index', 'admin/show/showcard.html.twig');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            BooleanField::new('is_valid', 'Validé')->onlyWhenUpdating(),
            BooleanField::new('is_monster', 'Monstre')->renderAsSwitch(false),
            BooleanField::new('is_trap', 'Piège')->renderAsSwitch(false),
            BooleanField::new('is_magic', 'Magie')->renderAsSwitch(false),
            TextField::new('name', 'Nom'),
            IntegerField::new('attack', 'Attaque'),
            IntegerField::new('defense', 'Défense'),
            TextareaField::new('description', 'Description')->setTemplatePath('admin/fields/descriptionmini.html.twig'),
            TextField::new('id_card', 'ID'),
            IntegerField::new('level', 'Nv./Rank'),
            IntegerField::new('pendulum_scale', 'Nv. Pendule'),
            TextareaField::new('pendulum_desc', 'Desc. Pendule')->setTemplatePath('admin/fields/descriptionmini.html.twig'),
            IntegerField::new('link_level', 'Nv. Lien'),
            AssociationField::new('attribute', 'Attribut'),
            AssociationField::new('typeMonster', 'Type Monstre')->setTemplatePath('admin/fields/typemonster.html.twig'),
            AssociationField::new('cardType', 'Type Carte')->setTemplatePath('admin/fields/typecard.html.twig'),
            AssociationField::new('icone', 'Type Magie/Piège'),
            AssociationField::new('rarity', 'Rareté'),
            AssociationField::new('relatedSet', 'Set'),
            ImageTo64Field::new('src'),
        ];
    }

}
