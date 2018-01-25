<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 24/01/2018
 * Time: 23:10
 */

namespace App\Form;


use App\Entity\Klantaccount;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddOrderKlantAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('klantPersoonlijkeGegevens', AddOrderKlantGegevensType::class);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Klantaccount::class
        ));
    }
}