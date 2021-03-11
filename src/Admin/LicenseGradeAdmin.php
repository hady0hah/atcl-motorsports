<?php


namespace App\Admin;


use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;

class LicenseGradeAdmin extends BaseAdmin
{
    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('gradeLetter')
            ->add('description', null, [
                'required' => false
            ])
            ->add('gradeType', ModelType::class, [
                'multiple' => false,
                'expanded' =>  false,
                'btn_add' => false
            ]);
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('gradeLetter')
            ->add('description')
            ->add('gradeType')
            ;
    }


}