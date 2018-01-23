<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 22/01/2018
 * Time: 12:30
 */

namespace App\Form;


use App\Entity\ActiePeriode;
use Doctrine\DBAL\Types\DecimalType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActiePeriodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('actiePeriodeStart', DateType::class, array(
                'format' => 'dd-MM-yyyy',
            ))
            ->add('actiePeriodeEinde', DateType::class, array(
                'format' => 'dd-MM-yyyy'
            ))
            ->add('actiePercentage', PercentType::class, array(
                'scale' => 3
            ));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ActiePeriode::class
        ));
}
}