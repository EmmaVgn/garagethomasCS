<?php

namespace App\Controller\Admin;

use App\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\String\Slugger\SluggerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TypeCrudController extends AbstractCrudController
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    
    public static function getEntityFqcn(): string
    {
        return Type::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Modèles')
            ->setPageTitle('new', 'Ajouter un modèle')
            ->setDefaultSort(['name' => 'ASC'])
            ->setPaginatorPageSize(10)
            ->setEntityLabelInSingular('un Modèle');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->onlyOnIndex(),
            TextField::new('name', 'Nom du modèle')
            ->setFormTypeOptions(['attr' => ['placeholder' => 'Ex: A3 SPORTBACK']]),
            TextField::new('slug', 'Slug')->onlyOnIndex(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->sluggerName($entityInstance);
        // Apply ucfirst to relevant fields
        $entityInstance->setName(strtoupper($entityInstance->getName())); 
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->sluggerName($entityInstance);
        $entityInstance->setName(strtoupper($entityInstance->getName())); 
        parent::updateEntity($entityManager, $entityInstance);
    }
    
    private function sluggerName(Type $type): void
    {
        $type->setSlug(strtolower($this->slugger->slug($type->getName())));
    }
}