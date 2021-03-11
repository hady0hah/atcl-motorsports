<?php

namespace App\Form;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeImmutableToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\ReversedTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MillisecondTimeType extends TimeType
{
    /**
     * Returns an array of extended types.
     */
    // public static function getExtendedTypes(): iterable
    // {
    //     // return [FormType::class] to modify (nearly) every field in the system
    //     return [TimeType::class];
    // }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        // makes it legal for FileType fields to have an image_property option
        $resolver->setDefined(['with_milliseconds']);
        $resolver->setDefaults([
            'with_milliseconds'=>false,
            'input_format' => 'H:i:s.v',
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
        if($options['with_milliseconds'] && 'single_text' === $options['widget']) {
            $format = 'H:i:s.v';
            
            $parts = ['hour','minute','second','millisecond'];
            $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $e) use ($options) {
                $data = $e->getData();
                if ($data && preg_match('/^(?P<hours>\d{2}):(?P<minutes>\d{2})(?::(?P<seconds>\d{2})(?:\.(?P<milliseconds>\d+))?)?$/', $data, $matches)) {
                    $e->setData(sprintf('%s:%s:%s.%s', $matches['hours'], $matches['minutes'], $matches['seconds'],$matches['milliseconds'] ?? '00'));
                }
            });
            
            if (null !== $options['reference_date']) {
                $format = 'Y-m-d '.$format;
                
                $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($options) {
                    $data = $event->getData();

                    if (preg_match('/^\d{2}:\d{2}(:\d{2})?(\.\d+)?$/', $data)) {
                        $event->setData($options['reference_date']->format('Y-m-d ').$data);
                    }
                });
            }
            
            $builder->addViewTransformer(new DateTimeToStringTransformer($options['model_timezone'], $options['view_timezone'], $format));
            
            if ('datetime_immutable' === $options['input']) {
                $builder->addModelTransformer(new DateTimeImmutableToDateTimeTransformer());
            } elseif ('string' === $options['input']) {
                $builder->addModelTransformer(new ReversedTransformer(
                    new DateTimeToStringTransformer($options['model_timezone'], $options['model_timezone'], $options['input_format'])
                ));
            } elseif ('timestamp' === $options['input']) {
                $builder->addModelTransformer(new ReversedTransformer(
                    new DateTimeToTimestampTransformer($options['model_timezone'], $options['model_timezone'])
                ));
            } elseif ('array' === $options['input']) {
                $builder->addModelTransformer(new ReversedTransformer(
                    new DateTimeToArrayTransformer($options['model_timezone'], $options['model_timezone'], $parts)
                ));
            }
        } else {
            parent::buildForm($builder, $options);
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if($options['with_milliseconds']) {
            $view->vars = array_replace($view->vars, [
                'widget' => $options['widget'],
                'with_minutes' => $options['with_minutes'],
                'with_seconds' => $options['with_seconds'],
                'with_milliseconds' => $options['with_milliseconds'],
            ]);

            // Change the input to an HTML5 time input if
            //  * the widget is set to "single_text"
            //  * the html5 is set to true
            if ($options['html5'] && 'single_text' === $options['widget']) {
                $view->vars['type'] = 'time';

                // we need to force the browser to display the seconds by
                // adding the HTML attribute step if not already defined.
                // Otherwise the browser will not display and so not send the seconds
                // therefore the value will always be considered as invalid.
                if (!isset($view->vars['attr']['step'])) {
                    $view->vars['attr']['step'] = 0.1;
                }
            }
        } else {
            parent::buildView($view,$form,$options);
        }
    }
}