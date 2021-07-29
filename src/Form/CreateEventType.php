<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Event;
use App\Entity\Location;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\TextType;
use SebastianBergmann\CodeCoverage\Report\Text;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            ->add('startDate', null, [
                'label' => 'Date et heure de la sortie :',
                'html5' => false,
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy HH:mm'
            ])
            ->add('registrationLimitDate', null, [
                'label' => 'Date limite d\'inscription :',
                'html5' => false,
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy HH:mm'
            ])
            ->add('maxRegistrations', null, [
                'label' => 'Nombre de places :'
            ])
            ->add('duration', null, [
                'label' => 'Durée :'
            ])
            ->add('infos', null, [
                'label' => 'Description et infos :'
            ])
            ->add('location', EntityType::class, [
                    'label' => 'Lieu :',
                    'class' => Location::class,
                    'choice_label' => 'name'
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
