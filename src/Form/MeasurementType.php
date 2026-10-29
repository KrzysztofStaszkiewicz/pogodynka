<?php

namespace App\Form;

use App\Entity\Measurement;
use App\Entity\Place;
use App\Repository\PlaceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeasurementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label' => 'Date',
            ])
            ->add('Temperature', IntegerType::class, [
                'label' => 'Temperature',
                'attr' => [
                    'placeholder' => 'np. 21'
                ]
            ])
            ->add('Wind', IntegerType::class, [
                'label' => 'Wind',
                'attr' => [
                    'placeholder' => 'np. 5'
                ]
            ])
            ->add('Cloudiness', TextType::class, [
                'label' => 'Cloudiness',
                'attr' => [
                    'placeholder' => 'Cloudiness'
                ]
            ])
            ->add('Precipitation', IntegerType::class, [
                'label' => 'Precipitation',
                'attr' => [
                    'placeholder' => 'Precipitation'
                ]
            ])
            ->add('place', EntityType::class, [
                'class' => Place::class,
                'choice_label' => static fn (Place $place): string => sprintf(
                    '%s - %s',
                    $place->getCountry(),
                    $place->getCity(),
                ),
                'placeholder' => 'Choose a country and city',
                'query_builder' => static fn (PlaceRepository $repository) => $repository
                    ->createQueryBuilder('place')
                    ->orderBy('place.Country', 'ASC')
                    ->addOrderBy('place.City', 'ASC'),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Measurement::class,
        ]);
    }
}
