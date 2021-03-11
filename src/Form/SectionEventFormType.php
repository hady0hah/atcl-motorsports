<?php


namespace App\Form;


use App\Entity\Section;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SectionEventFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'bannerFile',
                VichImageType::class,
                [
                    'required' => false,
                    'label' => 'Banner',
                ])
            ->add('name')
            ->add('label')
            ->add('startDate')
            ->add('endDate')
            ->add('location')
            ->add('description');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Section::class,
        ]);
    }

}