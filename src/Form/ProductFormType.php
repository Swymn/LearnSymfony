<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            -> add('name', TextType::class, [
                'label' => "Nom du produit",
                'attr' => [
                    'placeholder' => "Tapez le nom du produit"
                ]
            ])
            -> add('price', MoneyType::class, [
                'label' => "Prix du produit",
                'attr' => [
                    'placeholder' => "Tapez le prix du produit"
                ],
                'divisor' => 100,
            ])
            -> add('shortDescription', TextareaType::class, [
                'label' => "Description",
                'attr' => [
                    'placeholder' => "Tapez le description du produit"
                ]
            ])
            -> add('picture', UrlType::class, [
                'label' => "Image du Produit",
                'attr' => [
                    'placeholder' => "Tapez une URL d'image"
                ]
            ])
            -> add('category', EntityType::class, [
                'label' => "Category",
                'attr' => [
                    'placeholder' => "Sélectionnez la catégorie"
                ],
                'placeholder' => '-- Choisir une catégorie --',
                'class' => Category::class,
                'choice_label' => 'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver -> setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
