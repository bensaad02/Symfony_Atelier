<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuthorBookSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('minBooks', IntegerType::class, [
                'label' => 'Nombre minimum de livres',
                'required' => false,
                'attr' => [
                    'min' => 0,
                    'class' => 'form-control',
                    'placeholder' => '0'
                ]
            ])
            ->add('maxBooks', IntegerType::class, [
                'label' => 'Nombre maximum de livres',
                'required' => false,
                'attr' => [
                    'min' => 0,
                    'class' => 'form-control',
                    'placeholder' => '100'
                ]
            ])
            ->add('search', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => ['class' => 'btn btn-primary']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}