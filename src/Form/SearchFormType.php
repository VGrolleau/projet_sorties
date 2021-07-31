<?php


namespace App\Form;


use App\Data\SeachData;
use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
            ->add('sorties', CheckboxType::class, [
                'label'    => 'Sorties dont je suie l\'organisateur/trice',
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