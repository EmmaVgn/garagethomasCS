<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\Type\CustomDateType;
use App\Form\ProductImageFormType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use Symfony\Component\String\Slugger\SluggerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('index', 'Véhicules :')
            ->setPageTitle('new', 'Créer un véhicule')
            ->setPaginatorPageSize(10)
            ->setEntityLabelInSingular('un Véhicule');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            FormField::addColumn(6),
            TextField::new('name', 'Nom du véhicule'),
            MoneyField::new('price', 'Prix du véhicule')
            ->setCurrency('EUR')
            ->setTextAlign('left'),
            NumberField::new('kilometers', 'Kilométrage du véhicule')->setNumDecimals(0),
            ChoiceField::new('energy', 'Motorisation du véhicule')->setChoices([
                'Essence' => 'Essence',
                'Diesel' => 'Diesel',
                'Hybride' => 'Hybride',
                'Electrique' => 'Electrique',
            ]),
            DateField::new('circulationAt', 'Date de mise en circulation du véhicule')
            ->setFormType(CustomDateType::class)
            ->setFormat('yyyy')
            ->renderAsChoice(),
            CollectionField::new('images', 'Images du véhicule')
            ->setEntryType(ProductImageFormType::class)
            ->setFormTypeOption('by_reference', false)
            ->hideOnIndex(),
            FormField::addColumn(6),
            TextEditorField::new('shortDescription', 'Description courte du véhicule')->hideOnIndex(),
            AssociationField::new('category', 'Catégorie du véhicule'),
            AssociationField::new('model', 'Marque du véhicule')
                ->autocomplete(),
            AssociationField::new('type', 'Modèle du véhicule')->autocomplete()->hideOnIndex(),
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

    private function sluggerName(Product $product): void
    {
        $product->setSlug(strtolower($this->slugger->slug($product->getName())));
    }
    
}
