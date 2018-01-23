<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 22/01/2018
 * Time: 21:19
 */

namespace App\Form;

use App\Entity\Klantaccount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditCredentialsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                'disabled' => true,
                'label' => 'Gebruikersnaam',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('plainPassword', s::class, array(
                'type' => PasswordType::class,
                'first_options' => array(
                    'label' => 'Wachtwoord',
                    'data' => '',
                    'attr' => array(
                        'class' => 'form-control'
                    )
                ),
                'second_options' => array(
                    'label' => 'Herhaal wachtwoord',
                    'data' => '',
                    'attr' => array(
                        'class' => 'form-control tk-proxima-nova'
                    )
                ),
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('email', TextType::class, array(
                'label' => 'E-mail *',
                'attr' => array(
                    'class' => 'form-control'
                )
            ));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Klantaccount::class
        ));
    }
}