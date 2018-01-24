<?php
/**
 * Created by PhpStorm.
 * User: mohamedkaddouri
 * Date: 19-01-18
 * Time: 13:22
 */

namespace App\Form;


use App\Entity\Specificatie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpecificatieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('merk',TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'label' => 'Merk',
                    'label_attr' => array(
                        'class' => 'tk-prixima-nova'
                    ))
            )
            ->add('type',TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'label' => 'Model',
                    'label_attr' => array(
                        'class' => 'tk-prixima-nova'
                    ))
            )
            ->add('bouwjaar',NumberType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'label' => 'Bouwjaar',
                    'label_attr' => array(
                        'class' => 'tk-prixima-nova'
                    ))
            )
            ->add('massaInventaris',NumberType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'label' => 'Massa inventaris',
                    'label_attr' => array(
                        'class' => 'tk-prixima-nova'
                    ))
            )
            ->add('massaMax',NumberType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'label' => 'Maximale massa',
                    'label_attr' => array(
                        'class' => 'tk-prixima-nova'
                    ))
            )
            ->add('lengteTot',NumberType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'label' => 'Lengte tot',
                    'label_attr' => array(
                        'class' => 'tk-prixima-nova'
                    ))
            )
            ->add('lengteOpbouw',NumberType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'label' => 'Lengte inbouw',
                    'label_attr' => array(
                        'class' => 'tk-prixima-nova'
                    ))
            )
            ->add('hoogte',NumberType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'label' => 'Hoogte',
                    'label_attr' => array(
                        'class' => 'tk-prixima-nova'
                    ))
            )
            ->add('rijbewijsBenodigd', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'label' => 'Rijbewijs benodigd',
                    'label_attr' => array(
                        'class' => 'tk-prixima-nova'
                    ))
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Specificatie::class
        ));
    }
}