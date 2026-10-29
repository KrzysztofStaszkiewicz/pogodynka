<?php

namespace App\Form;

use App\Entity\Place;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Country', ChoiceType::class, [
                'choices' => [
                    'France' => 'FR',
                    'Italy' => 'IT',
                    'Spain' => 'ES',
                    'Germany' => 'DE',
                    'Poland' => 'PL',
                    'United States' => 'EN',
                    'United Kingdom' => 'GB',
                ]
            ])
            ->add('City', null, [
                'attr' => [
                    'placeholder' => 'Enter city name',
                ],
            ])
            ->add('Latitude', NumberType::class, [
                'scale' => 7,
                'attr' => [
                    'placeholder' => 'Enter latitude',
                    'max' => 90,
                    'min' => -90,
                ]
            ])
            ->add('Longitude', NumberType::class, [
                'scale' => 7,
                'attr' => [
                    'placeholder' => 'Enter longitude',
                    'max' => 90,
                    'min' => -90,
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}
