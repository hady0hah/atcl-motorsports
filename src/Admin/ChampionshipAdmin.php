<?php

declare(strict_types=1);

namespace App\Admin;

use App\Utilities\NameGenerator;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Vich\UploaderBundle\Form\Type\VichImageType;

final class ChampionshipAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('label')
            ->add('year')
            ->add('description');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('label')
            ->add('year')
            ->add('description')
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper

            ->add(
                'bannerFile',
                VichImageType::class,
                [
                    'required' => false,
                    'label' => 'Banner',
                ])
            ->add('label')
            ->add('year')
            ->add('description');
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
