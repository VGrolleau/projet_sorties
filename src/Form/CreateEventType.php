<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Event;
use App\Entity\Location;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom de la sortie :'
            ])
            ->add('startDate', DateTimeType::class,[
                'label' => 'Date et heure de la sortie :',
                'html5' => false,
                'widget' => 'single_text',
                'attr' => ['class' => 'flatpickrDateTime'],
                'format' => 'dd/MM/yyyy HH:ss'
            ])
            ->add('duration', null, [
                'label' => 'Durée (en minutes) :'
            ])
            ->add('registrationLimitDate', DateType::class, [
                'label' => 'Date limite d\'inscription :',
                'html5' => false,
                'widget' => 'single_text',
                'attr' => ['class' => 'flatpickrDate'],
                'format' => 'dd/MM/yyyy'
            ])
            ->add('maxRegistrations', null, [
                'label' => 'Nombre de places :'
            ])
            ->add('infos', null, [
                'label' => 'Description et infos :'
            ])
            ->add('campus', EntityType::class, [
                'label' => 'Campus :',
                'class' => Campus::class,
                'choice_label' => 'name'
            ])
            ->add('location', EntityType::class, [
                'label' => 'Lieu :',
                'class' => Location::class,
                'choice_label' => 'name'
            ])
            ->add('publish', SubmitType::class, [
                'label' => 'Publier la sortie'
            ])
            ->add('registerEvent', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
