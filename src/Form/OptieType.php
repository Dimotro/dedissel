<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 21/01/2018
 * Time: 12:02
 */

namespace App\Form;


use App\Entity\OptieProduct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OptieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('optieTitel', TextType::class,
                array(
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'label' => 'Titel',
                    'label_attr' => array(
                        'class' => 'tk-prixima-nova'
                    )
                )
            )
            ->add('optieOmschrijving', TextType::class,
                array(
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'label' => 'Omschrijving',
                    'label_attr' => array(
                        'class' => 'tk-prixima-nova'
                    )
                )
            )
            ->add('optiePrijs', NumberType::class, array(
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'label' => 'Prijs',
                    'label_attr' => array(
                        'class' => 'tk-prixima-nova'
                    )
                )
            )
            ->add('fotos',FileType::class, array(
                'attr' => array(
                    'class' => 'form-control'
                ),
                'label' => "Foto's",
                'multiple' => true
            ));

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => OptieProduct::class,
        ));    }
}