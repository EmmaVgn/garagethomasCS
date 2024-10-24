<?php

namespace App\Controller\Admin;

use App\Entity\Energy;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EnergyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Energy::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Motorisation')
            ->setPageTitle('new', 'Ajouter une motorisation')
            ->setDefaultSort(['id' => 'ASC'])
            ->setPaginatorPageSize(10)
            ->setEntityLabelInSingular('une Motorisation');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->onlyOnIndex(),
            TextField::new('name', 'Nom de la motorisation')
            ->setFormTypeOptions(['attr' => ['placeholder' => 'Ex: Diesel']]),
            TextField::new('slug', 'Slug')->onlyOnIndex(),
        ];
    }
    
}