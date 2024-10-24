<?php

namespace App\Controller\Admin;

use App\Entity\Critair;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CritairCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Critair::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Crit\'Air')
            ->setPageTitle('new', 'Ajouter un Crit\'air')
            ->setDefaultSort(['id' => 'ASC'])
            ->setPaginatorPageSize(10)
            ->setEntityLabelInSingular('une Crit\'air');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->onlyOnIndex(),
            TextField::new('name', 'Niveau du Crit\'Air')
            ->setFormTypeOptions(['attr' => ['placeholder' => 'Ex: Niveau 0']]),
            TextField::new('slug', 'Slug')->onlyOnIndex(),
        ];
    }
    
}