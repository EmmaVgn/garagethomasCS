<?php

namespace App\Form;

use App\Entity\Model;
use App\Data\SearchData;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('categories', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'expanded' => true,
                'multiple' => true,
                'choice_label' => function (Category $category): string {
                    return $category->getName() . ' (' . $category->getProducts()->count() . ')';
                }
            ])
            ->add('model', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Model::class,
                'expanded' => true,
                'multiple' => true,
                'choice_label' => function (Model $model): string {
                    return $model->getName() . ' (' . $model->getProducts()->count() . ')';
                }
            ])
            ->add('minPrice', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Prix min'
                ],
            ])
            ->add('maxPrice', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Prix max'
                ],
            ])
            ->add('minKms', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Kilomètres min'
                ],
            ])
            ->add('maxKms', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Kilomètres max'
                ],
            ])
            ->add('minCirculationAt', DateTimeType::class, [
                'label' => false,
                'required' => false,
                'format' => 'yyyy',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'placeholder' => 'Année min'
                ],
            ])
            ->add('maxCirculationAt', DateTimeType::class, [
                'label' => false,
                'required' => false,
                'widget' => 'single_text',
                'format' => 'yyyy',
                'html5' => false,
                'attr' => [
                    'placeholder' => 'Année max'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}