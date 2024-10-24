<?php

namespace App\Controller\Admin;

use App\Entity\Model;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\String\Slugger\SluggerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ModelCrudController extends AbstractCrudController
{

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
            TextField::new('imageFile', 'Fichier image :')
            ->setFormType(VichImageType::class)
            -> setTranslationParameters([ 'form.label.delete' => 'Supprimer l\'image' ])
            ->hideOnIndex(),
            ImageField::new('imageName', 'Logo')
            ->setBasePath('/images/models')
            ->onlyOnIndex(),
        ];
    }
    
}