<?php


namespace App\Admin;


use App\Form\DynamicFormType;
use App\Utilities\NameGenerator;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GradeTypeAdmin extends BaseAdmin
{
    protected function configureFormFields(FormMapper $form)
    {
        $form
//            ->add('name', null, [
//                'label' => 'Grade Code'
//            ])
            ->add('label')
            ->add('config', DynamicFormType::class, [
                'entries' =>
                    [
                        [
                            'name' => 'startSequence',
                            'type' => NumberType::class,
                            'options' => ['required' => true]
                        ],
                        [
                            'name' => 'endSequence',
                            'type' => NumberType::class,
                            'options' => ['required' => true]
                        ],
                    ],
                'label' => false,
            ]);
    }

    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('name', null, [
                'label' => 'Grade Code'
            ])
            ->add('label')
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                ],
            ]);;
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