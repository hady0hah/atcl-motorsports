<?php


namespace App\Admin;


use App\Utilities\NameGenerator;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class DocumentAdmin extends AbstractAdmin
{
    protected function configureBatchActions($actions)
    {
        unset($actions['delete']);
        return $actions;
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('documentCategory')
            ->add('label')
            ->add('document')
            ->add('_action', null, [
                'actions' => [
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('label', null, [
                'label' => 'Title',
                'required' => true
            ])
            ->add('documentCategory', ModelType::class, [
            'multiple' => false,
            'btn_add' => false,
            'btn_delete' => false,
            'btn_list' => 'Categories',
            'required' => false,
            'placeholder' => "Enter a category"
        ]);
        $form->add('documentFile', VichFileType::class, [
            'required' => false
        ]);
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('documentCategory');
    }

    public function prePersist($object)
    {
        NameGenerator::defaultSave($object);
    }

    public function preUpdate($object)
    {
        NameGenerator::defaultSave($object);
    }
}