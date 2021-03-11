<?php

declare(strict_types=1);

namespace App\Admin;

use App\Utilities\NameGenerator;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;

final class DocumentCategoryAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('label')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('label')
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('label')
            ->add('parent', ModelType::class, [
                'multiple' => false,
                'btn_add' => false,
                'btn_delete' => false,
                'required' => false,
                'placeholder' => "Choose a parent category"
            ])
            ;
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
