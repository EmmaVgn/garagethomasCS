<?php

namespace App\Controller\Admin;

use App\Entity\Color;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\String\Slugger\SluggerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ColorCrudController extends AbstractCrudController
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    
    public static function getEntityFqcn(): string
    {
        return Color::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Couleurs')
            ->setPageTitle('new', 'Ajouter une couleur')
            ->setDefaultSort(['name' => 'ASC'])
            ->setPaginatorPageSize(10)
            ->setEntityLabelInSingular('une Couleur');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->onlyOnIndex(),
            TextField::new('name', 'Nom de la couleur')
                ->setFormTypeOptions(['attr' => ['placeholder' => 'Ex: Blanc']]),
            TextField::new('slug', 'Slug')->onlyOnIndex(),
            TextField::new('imageFile', 'Fichier image :')
            ->setFormType(VichImageType::class)
            -> setTranslationParameters([ 'form.label.delete' => 'Supprimer l\'image' ])
            ->hideOnIndex(),
            ImageField::new('imageName', 'Logo')
            ->setBasePath('/images/colors')
            ->onlyOnIndex(),
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

    private function sluggerName(Color $color): void
    {
        $color->setSlug(strtolower($this->slugger->slug($color->getName())));
    }
    
}