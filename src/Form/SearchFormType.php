<?php


namespace App\Form;


use App\Data\LocationData;
use App\Data\SeachData;
use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', EntityType::class,[
                'label' => false,
                'class' => Campus::class,
            ])

            ->add('q', TextType::class, [
                    'label' => false,
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Rechercher'
                    ]
                ])

            ->add('start_Date', DateTimeType::class,[
                'html5' => true,
                'widget' => 'single_text',
                'label' => 'Entre :',
                'data'   => new \DateTime('now',new \DateTimeZone('europe/paris')),
                'attr'   => ['start_Date' => ( new \DateTime() )->format('d-m-Y H:i')],
            ])

            ->add('end_Date', DateTimeType::class,[
                'html5' => true,
                'widget' => 'single_text',
                'label' => 'et',
                'data'   => new \DateTime('now',new \DateTimeZone('europe/paris')),
                'attr'   => ['end_Date' => ( new \DateTime() )->format('d-m-Y H:i')]
            ])

            ->add('sorties', CheckboxType::class, [
                'label'    => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false,
            ])
            ->add('sorties2', CheckboxType::class, [
                'label'    => 'Sorties auxquelles je suis inscrit/e',
                'required' => false,
            ])
            ->add('sorties3', CheckboxType::class, [
                'label'    => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false,
            ])
            ->add('sorties4', CheckboxType::class, [
            'label'    => 'Sorties passées',
            'required' => false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SeachData::class,
            'method' => 'GET',
            'csrf_protection'=> false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}