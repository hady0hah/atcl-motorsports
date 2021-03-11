<?php


namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LicenseNumberType extends AbstractType
{
    public function buildYearChoices() {
        $distance = 5;
        $yearsBefore = date('Y', mktime(0, 0, 0, date("m"), date("d"), date("Y") - $distance));
        $yearsAfter = date('Y', mktime(0, 0, 0, date("m"), date("d"), date("Y") + $distance));
        return array_combine(range($yearsBefore, $yearsAfter), range($yearsBefore, $yearsAfter));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number' , TextType::class)
            ->add('year', ChoiceType::class,
                [
                    'required' => true,
                    'choices' => $this->buildYearChoices()
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}