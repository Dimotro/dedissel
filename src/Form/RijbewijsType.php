<?php
/**
 * Created by PhpStorm.
 * User: imoz
 * Date: 23/01/2018
 * Time: 00:34
 */

namespace App\Form;


use App\Entity\Rijbewijs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RijbewijsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rijbewijsnummer', NumberType::class, array(
                'label' => 'Rijbewijsnummer',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('rijbewijsA', CheckboxType::class, array(
                'label' => 'A',
                'property_path' => 'rijbewijsType[A]',
                'required' => false,
                'attr' => array(
                    'class' => 'form-check-input'
                ),
                'label_attr' => array(
                    'class' => 'form-check-label'
                )
            ))
            ->add('rijbewijsA1', CheckboxType::class, array(
                'label' => 'A1',
                'property_path' => 'rijbewijsType[A1]',
                'required' => false,
                'attr' => array(
                    'class' => 'form-check-input'
                ),
                'label_attr' => array(
                    'class' => 'form-check-label'
                )
            ))
            ->add('rijbewijsA2', CheckboxType::class, array(
                'label' => 'A2',
                'property_path' => 'rijbewijsType[A2]',
                'required' => false,
                'attr' => array(
                    'class' => 'form-check-input'
                ),
                'label_attr' => array(
                    'class' => 'form-check-label'
                )
            ))
            ->add('rijbewijsAM', CheckboxType::class, array(
                'label' => 'AM',
                'property_path' => 'rijbewijsType[AM]',
                'required' => false,
                'attr' => array(
                    'class' => 'form-check-input'
                ),
                'label_attr' => array(
                    'class' => 'form-check-label'
                )
            ))
            ->add('rijbewijsB', CheckboxType::class, array(
                'label' => 'B',
                'property_path' => 'rijbewijsType[B]',
                'required' => false,
                'attr' => array(
                    'class' => 'form-check-input'
                ),
                'label_attr' => array(
                    'class' => 'form-check-label'
                )
            ))
            ->add('rijbewijsBE', CheckboxType::class, array(
                'label' => 'BE',
                'property_path' => 'rijbewijsType[BE]',
                'required' => false,
                'attr' => array(
                    'class' => 'form-check-input'
                ),
                'label_attr' => array(
                    'class' => 'form-check-label'
                )
            ))
            ->add('rijbewijsBPlus', CheckboxType::class, array(
                'label' => 'B+',
                'property_path' => 'rijbewijsType[BPlus]',
                'required' => false,
                'attr' => array(
                    'class' => 'form-check-input'
                ),
                'label_attr' => array(
                    'class' => 'form-check-label'
                )
            ))
            ->add('rijbewijsC', CheckboxType::class, array(
                'label' => 'C',
                'property_path' => 'rijbewijsType[C]',
                'required' => false,
                'attr' => array(
                    'class' => 'form-check-input'
                ),
                'label_attr' => array(
                    'class' => 'form-check-label'
                )
            ))
            ->add('rijbewijsCE', CheckboxType::class, array(
                'label' => 'CE',
                'property_path' => 'rijbewijsType[CE]',
                'required' => false,
                'attr' => array(
                    'class' => 'form-check-input'
                ),
                'label_attr' => array(
                    'class' => 'form-check-label'
                )
            ))
            ->add('rijbewijsC1', CheckboxType::class, array(
                'label' => 'C1',
                'property_path' => 'rijbewijsType[C1]',
                'required' => false,
                'attr' => array(
                    'class' => 'form-check-input'
                ),
                'label_attr' => array(
                    'class' => 'form-check-label'
                )
            ))
            ->add('rijbewijsC1E', CheckboxType::class, array(
                'label' => 'C1E',
                'property_path' => 'rijbewijsType[C1E]',
                'required' => false,
                'attr' => array(
                    'class' => 'form-check-input'
                ),
                'label_attr' => array(
                    'class' => 'form-check-label'
                )
            ))
            ->add('rijbewijsD', CheckboxType::class, array(
                'label' => 'D',
                'property_path' => 'rijbewijsType[D]',
                'required' => false,
                'attr' => array(
                    'class' => 'form-check-input'
                ),
                'label_attr' => array(
                    'class' => 'form-check-label'
                )
            ))
            ->add('rijbewijsDE', CheckboxType::class, array(
                'label' => 'A',
                'property_path' => 'rijbewijsType[DE]',
                'required' => false,
                'attr' => array(
                    'class' => 'form-check-input'
                ),
                'label_attr' => array(
                    'class' => 'form-check-label'
                )
            ))
            ->add('rijbewijsD1', CheckboxType::class, array(
                'label' => 'D1',
                'property_path' => 'rijbewijsType[D1]',
                'required' => false,
                'attr' => array(
                    'class' => 'form-check-input'
                ),
                'label_attr' => array(
                    'class' => 'form-check-label'
                )
            ))
            ->add('rijbewijsD1E', CheckboxType::class, array(
                'label' => 'D1E',
                'property_path' => 'rijbewijsType[D1E]',
                'required' => false,
                'attr' => array(
                    'class' => 'form-check-input'
                ),
                'label_attr' => array(
                    'class' => 'form-check-label'
                )
            ))
            ->add('rijbewijsT', CheckboxType::class, array(
                'label' => 'T',
                'property_path' => 'rijbewijsType[T]',
                'required' => false,
                'attr' => array(
                    'class' => 'form-check-input'
                ),
                'label_attr' => array(
                    'class' => 'form-check-label'
                )
            ))
            ->add('rijbewijsGeldigtot', DateType::class, array(
                'label' => 'Geldig tot',
                'attr' => array(
                    'class' => 'form-control'
                )
            ));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Rijbewijs::class
        ));
    }
}