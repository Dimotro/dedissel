<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 20/01/2018
 * Time: 13:54
 */

namespace App\Form;


use App\Entity\Klantaccount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, array('label' => 'E-mailadres'))
            ->add('username', TextType::class, array('label' => 'Gebruikersnaam'))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Wachtwoord', 'attr' => array('class' => 'form-control')),
                'second_options' => array('label' => 'Wachtwoord herhalen', 'attr' => array('class' => 'form-control')),
            ))
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Klantaccount::class,
        ));
    }
}