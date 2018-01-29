<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 24/01/2018
 * Time: 20:09
 */

namespace App\Form;


use App\Entity\ObjectProduct;
use App\Entity\ObjectProductPeriod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditObjectDateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datumUit', DateType::class, array(
                'html5' => true,
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control datumUit'
                )
            ))
            ->add('datumTerug', DateType::class, array(
                'html5' => true,
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control datumTerug'
            )
        ));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ObjectProductPeriod::class
        ));
    }
}