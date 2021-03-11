<?php


namespace App\Admin;


use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class LicenseTypeAdmin extends BaseAdmin
{
    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('name', null, ['label' => 'Code'])
            ->add('label')
        ;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('name', null, ['label' => 'Code'])
            ->add('label')
        ;
    }
}