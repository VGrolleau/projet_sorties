<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', EntityType::class, [
                'label' => 'Ville :',
                'class' => City::class,
                'choice_label' => 'name'
            ])
            ->add('name', null, [
                'label' => 'Lieu :'
            ])
            ->add('street', null, [
                'label' => 'Rue :'
            ])
            ->add('latitude', null, [
                'label' => 'Latitude :'
            ])
            ->add('longitude', null, [
                'label' => 'Longitude :'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
