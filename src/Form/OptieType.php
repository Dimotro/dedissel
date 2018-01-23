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
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OptieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('optieTitel')
            ->add('optieOmschrijving')
            ->add('optiePrijs');

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => OptieProduct::class,
        ));    }
}