<?php

namespace App\Controller\Admin;

use App\Entity\Model;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\String\Slugger\SluggerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ModelCrudController extends AbstractCrudController
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public static function getEntityFqcn(): string
    {
        return Model::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Marques')
            ->setPageTitle('new', 'Ajouter une marque')
            ->setDefaultSort(['name' => 'ASC'])
            ->setPaginatorPageSize(10)
            ->setEntityLabelInSingular('une Marque');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->onlyOnIndex(),
            TextField::new('name', 'Nom de la marque')
                ->setFormTypeOptions(['attr' => ['placeholder' => 'Ex: Peugeot']]),
            TextField::new('slug', 'Slug')->onlyOnIndex(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->sluggerName($entityInstance);
        // Apply ucfirst to relevant fields
        $entityInstance->setName(ucfirst($entityInstance->getName())); 
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->sluggerName($entityInstance);
        $entityInstance->setName(ucfirst($entityInstance->getName())); 
        parent::updateEntity($entityManager, $entityInstance);
    }

    private function sluggerName(Model $model): void
    {
        $model->setSlug(strtolower($this->slugger->slug($model->getName())));
    }
    
}