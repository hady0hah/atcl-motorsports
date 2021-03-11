<?php


namespace App\Admin;


use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\Form\Type\DatePickerType;
use Sonata\Form\Type\DateTimePickerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class LicenseGradePriceAdmin extends BaseAdmin
{
    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('licenseGrade', ModelType::class, [
                'label' => 'Grade',
                'multiple' => false,
                'expanded' => false,
                'btn_add' => false,
                'placeholder' => 'Enter a grade'
            ])
            ->add('price')
            ->add('year', DatePickerType::class, [
                'format' => 'yyyy',
                'dp_view_mode' => 'years',
                'dp_min_view_mode' => 'years',
            ]);
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('licenseGrade', null, ['label' => 'Grade'])
            ->add('price')
            ->add('year')
            ;
    }
}