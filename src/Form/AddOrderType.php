<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 24/01/2018
 * Time: 16:27
 */

namespace App\Form;


use App\Entity\KlantOrder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('klant', AddOrderKlantAccountType::class)
            ->add('objectProduct', EditObjectDateType::class);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => KlantOrder::class
        ));
    }
}