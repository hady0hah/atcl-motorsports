<?php


namespace App\Form;


use App\Entity\Driver;
use App\Entity\Participant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ordering', null, ['label' => 'Order','row_attr'=>['class'=>'col-md-1']])
            ->add('number',null,['row_attr'=>['class'=>'col-md-1']])
            ->add('driver', EntityType::class, [
                'class' => Driver::class,
                'row_attr'=>['class'=>'col-md-2']
            ])
            ->add('coDriver', EntityType::class, [
                'class' => Driver::class,
                'required' => false,
                'row_attr'=>['class'=>'col-md-2']
            ])
            ->add('car',null,['row_attr'=>['class'=>'col-md-3']])
            ->add('gap', null, ['attr'=>['placeholder' => 'minutes'],'row_attr'=>['class'=>'col-md-1']])
            ->add('dnf', null,['row_attr'=>['class'=>'col-md-1']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class
        ]);
    }
}