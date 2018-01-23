<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 21/01/2018
 * Time: 16:32
 */

namespace App\Form;


use App\Entity\Klantaccount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KlantaccountResetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Wachtwoord', 'always_empty' => true),
                'second_options' => array('label' => 'Wachtwoord herhalen', 'always_empty' => true),
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Klantaccount::class
        ));
    }
}