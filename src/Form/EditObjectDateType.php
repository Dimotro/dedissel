<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 24/01/2018
 * Time: 20:09
 */

namespace App\Form;


use App\Entity\ObjectProduct;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditObjectDateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('objDatumUit', DateType::class, array(
                'label' => 'Datum afgifte',
                'attr' => array(
                    'class' => 'form-control datumUit',
                ),
                'widget' => 'single_text'
            ))
            ->add('objDatumTerug', DateType::class ,array(
                'label' => 'Datum inname',
                'attr' => array(
                    'class' => 'form-control datumTerug',
                ),
                'widget' => 'single_text'
            ));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ObjectProduct::class
        ));
    }
}