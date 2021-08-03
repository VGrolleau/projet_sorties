<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Event;
use App\Entity\EventState;
use App\Entity\Location;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
//use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityRepository;
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
//            ->add('startDate', DateTimeType::class,[
//                'label' => 'Date et heure de la sortie :',
//                'data'   => new \DateTime(),
//                'attr'   => ['min' => ( new \DateTime() )->format('d-m-Y H:i')]
//            ])
            ->add('startDate', DateType::class,[
                'label' => 'Date et heure de la sortie :',
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('duration', null, [
                'label' => 'Durée :'
            ])
//            ->add('registrationLimitDate', null, [
//                'label' => 'Date limite d\'inscription :',
//                'data'   => new \DateTime(),
//                'attr'   => ['min' => ( new \DateTime() )->format('d-m-Y H:i')]
//            ])
            ->add('registrationLimitDate', DateType::class, [
                'label' => 'Date limite d\'inscription :',
                'html5' => true,
                'widget' => 'single_text'
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
            ->add('eventState', EntityType::class, [
                'label' => 'État de la sortie :',
                'class' => EventState::class,
                'choice_label' => 'name'
            ])
            ->add('location', EntityType::class, [
                'label' => 'Lieu :',
                'class' => Location::class,
                'choice_label' => 'name'
            ])
//            ->add('city', EntityType::class, [
//                'label' => 'Ville :',
//                'class' => City::class,
//                'choice_label' => 'name'
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
