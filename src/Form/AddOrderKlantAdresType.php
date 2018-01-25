<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 24/01/2018
 * Time: 23:16
 */

namespace App\Form;


use App\Entity\Klantadres;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddOrderKlantAdresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('klantStraat', TextType::class, array(
                'label' => 'Straat',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('klantHuisnummer', TextType::class, array(
                'label' => 'Huisnummer',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('klantWoonplaats', TextType::class, array(
                'label' => 'Woonplaats',
                'attr' => array(
                    'class' => 'form-control'
                )
            ));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Klantadres::class
        ));
    }
}