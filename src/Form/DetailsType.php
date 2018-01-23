<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 22/01/2018
 * Time: 22:21
 */

namespace App\Form;


use App\Entity\Klantadres;
use App\Entity\Klantgegeven;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('klantVoorletters', TextType::class, array(
                'label' => 'Voorletters',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('klantVoornaam', TextType::class, array(
                'label' => 'Voornaam',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('klantTussenvoegsel', TextType::class, array(
                'label' => 'Tussenvoegsel',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('klantAchternaam', TextType::class, array(
                'label' => 'Achternaam',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('klantTelefoon', TextType::class, array(
                'label' => 'Telefoon',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('klantMobiel', TextType::class, array(
                'label' => 'Mobiel',
                'attr' => array(
                    'class' => 'form-control'
                )
            ));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Klantgegeven::class
        ));
    }
}