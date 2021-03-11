<?php


namespace App\Form;


use App\Utilities\Utilities;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DynamicFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->recursiveBuildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'entries'=>[],
            'required' => false
        ]);
    }

    protected function recursiveBuildForm($builder, array $options) {
        foreach($options['entries'] as $entry) {
            $entry_name = array_key_exists('name',$entry) ? $entry['name'] : 'dft_'.rand(0,1000);
            $entry_type = array_key_exists('type',$entry) ? $entry['type'] : TextType::class;
            $entry_options = array_key_exists('options',$entry) ? $entry['options'] : [];
            if(!array_key_exists('label',$entry_options)) {
                $entry_options['label'] = Utilities::humanizeString($entry_name);
            }

            $builder
                ->add($entry_name,$entry_type,$entry_options);

            if(array_key_exists('entries',$entry)){
                $b = $builder->get($entry_name);
                $this->recursiveBuildForm($b, $entry);
            }

        }
    }
}