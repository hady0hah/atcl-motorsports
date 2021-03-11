<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Championship;
use App\Entity\Event;
use App\Entity\Result;
use App\Entity\Section;
use App\Form\DocumentType;
use App\Form\ParticipantType;
use App\Utilities\NameGenerator;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Type\CollectionType;
use Sonata\Form\Type\DateTimePickerType;
use Sonata\Form\Validator\ErrorElement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Sonata\AdminBundle\Route\RouteCollection;


final class EventAdmin extends AbstractAdmin
{
    protected $datagridValues = [
        '_sort_order'=>'DESC',
        '_sort_by'=>'startDate'
    ];

    private function participantValidation(ErrorElement $errorElement, $object)
    {
        $participants = $object->getParticipants();

        foreach ($participants as $participant) {

            if ($participant->getDriver() == $participant->getCoDriver()) {
                $errorElement->addViolation("{$participant->getDriver()} can't be driver and co-driver at the same time");
            }
            if (count($participants->filter(function ($element) use ($participant) {
                return $element->getDriver() == $participant->getDriver();
            })) > 1 || $participants->exists(function ($key, $element) use ($participant) {
                return $element->getDriver() == $participant->getCoDriver();
            })) {
                $errorElement->addViolation("{$participant->getDriver()} exists more than once in this event");
                return;
            }
            if (count($participants->filter(function ($element) use ($participant) {
                return $element->getCoDriver() == $participant->getCoDriver();
            })) > 1 || $participants->exists(function ($key, $element) use ($participant) {
                return $element->getCoDriver() == $participant->getDriver();
            })) {
//                $errorElement->addViolation("{$participant->getCoDriver()} exists more than once in this event");
                return;
            }
        }
    }

    private function eventTimeValidation(ErrorElement $errorElement, $object)
    {
        if ($object->getStartDate() >= $object->getEndDate())
            $errorElement->addViolation("Event date is invalid");
    }

    private function topBannerValidation(ErrorElement  $errorElement, $object)
    {
        if ($object->getIsTopBanner() && !$object->getBanner())
            $errorElement->addViolation('You should have a banner image in order to put it as top');
    }
    public function validate(ErrorElement $errorElement, $object)
    {
        $this->participantValidation($errorElement, $object);
        $this->eventTimeValidation($errorElement, $object);
        $this->topBannerValidation($errorElement, $object);
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->add('section', $this->getRouterIdParameter() . '/section/list')
            ->add('result', $this->getRouterIdParameter() . '/result/list')
            ->add('results', $this->getRouterIdParameter() . '/results') // used now for RallyEvents
            ->add('start', $this->getRouterIdParameter() . '/start')
            ->add('stopEvents',  'stop');
        //            ->add('participant', $this->getRouterIdParameter() . '/eventparticipant/list');


    }


    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('label')
            ->add('startDate')
            ->add('endDate')
            ->add('location')
            ->add('description');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('label')
            ->add('startDate')
            ->add('endDate')
            ->add('location')
            ->add('description')
            ->add('published', null, ['editable'=>true])
            ->add('isTopBanner', null, ['editable'=>true])
            ->add('_action', null, [
                'actions' => [
                    'start' => ['template' => 'Admin\start_event_button.html.twig', 'route' => 'start'],
                    'edit' => [],
                    'delete' => [],
                    'section' => ['template' => 'list_button.html.twig', 'route' => 'section', 'label' => 'Sections', 'icon' => 'fa fa-plus'],
                    'result' => ['template' => 'list_results_button.html.twig', 'label' => 'Results', 'icon' => 'fa fa-th-list'],
                    //                    'participant' => ['template' => 'list_button.html.twig', 'route' => 'participant', 'label' => 'Participants', 'icon' => 'fa fa-th-list'],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $event = $this->getSubject();
        $isDisabled = false;

        if (($event->getResults() && count($event->getResults()) > 0) && $event->getId() !== null)
            $isDisabled = true;

        $formMapper
            ->tab('Event')
            ->with('Event')
            ->add('championship', EntityType::class, [
                'class' => Championship::class,
                'disabled' => $isDisabled,
            ])
            ->add(
                'bannerFile',
                VichImageType::class,
                [
                    'required' => false,
                    'label' => 'Banner',
//                    'disabled' => $isDisabled,

                ]
            )
            ->add(
                'imageFile',
                VichImageType::class,
                [
                    'required' => false,
                    'label' => 'Event Image',
                    'disabled' => $isDisabled,
                ]
            )

            //            ->add('name', null, [
            //                'disabled' => $isDisabled,
            //            ])
            ->add('label', null, [
//                'disabled' => $isDisabled,
            ])
            ->add('startDate', DateTimePickerType::class, [
                'format' => "yyyy-MM-dd HH:mm:ss",
                'disabled' => $isDisabled,
            ])
            ->add('endDate', DateTimePickerType::class, [
                'format' => "yyyy-MM-dd HH:mm:ss",
                'disabled' => $isDisabled,
            ])
            ->add('location', null, [
//                'disabled' => $isDisabled,

            ])
            ->add('description', null, [
//                'disabled' => $isDisabled,

            ])
            ->add('isTopBanner', null, [
//                'disabled' => $isDisabled,

            ])
            ->add('resultType', ChoiceType::class, [
                'disabled' => $isDisabled,
                'choices' => [
                    'Cumulative' => Result::CUMULATIVE,
                    'Points' => 'point',
                    'Start Time | End Time' => 'startend',
                    'Timer' => 'timer',
                ]
            ])
            //            ->add('documents', CollectionType::class, [
            //                'disabled' => $isDisabled,
            //                'required' => false,
            //                'type_options' => [
            //                    // Prevents the "Delete" option from being displayed
            //                    'delete' => true,
            ////                    'delete_options' => [
            ////                        // You may otherwise choose to put the field but hide it
            ////                        'type' => HiddenType::class,
            ////                        // In that case, you need to fill in the options as well
            ////                        'type_options' => [
            ////                            'mapped' => false,
            ////                            'required' => false,
            ////                        ]
            ////                    ]
            //                ]
            //            ], [
            //                'edit' => 'inline',
            //                'inline' => 'table',
            //                'sortable' => 'position',
            //            ])
            ->end()
            ->end()
            ->tab('Documents')
            ->with('Documents')
            ->add('documents', \Sonata\AdminBundle\Form\Type\CollectionType::class, [
                'required' => true,
                'entry_type' => DocumentType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
                'by_reference' => false,
            ], [
                'edit' => 'inline',
                'inline' => 'table',
                'sortable' => 'position',
            ])
            ->end()
            ->end()
            ->tab('Participants')
            ->with('Participants')
            ->add('participants', \Sonata\AdminBundle\Form\Type\CollectionType::class, [
                'required' => true,
                'entry_type' => ParticipantType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false,
                'by_reference' => false,
            ], [
                'edit' => 'inline',
                'inline' => 'table',
                'sortable' => 'position',
            ])
            ->end()
            ->end();
    }


    public function prePersist($event)
    {
        // document preview is not showing on validation error..
        // and documents can throw an error
        foreach ($event->getDocuments() as $key => $document) {
            if (!$document->getDocumentFile()) {
                unset($event->getDocuments()[$key]);
            }
        }
        NameGenerator::defaultSave($event);
    }

    public function preUpdate($event)
    {
        NameGenerator::defaultSave($event);
        $this->prePersist($event);
    }

    public function startEvent($event=NULL) 
    {
        if(!$event) $event = $this->getSubject();
        if(!$event) return false;
        if ($event->getResults() && count($event->getResults()) > 0) {
            return true;
        }

        /** @var ModelManager $em */
        $em = $this->getModelManager();
        $em = $em->getEntityManager($event);
        
        /** @var ResultAdmin $resultAdmin */
        $resultAdmin = $this->getConfigurationPool()->getAdminByAdminCode('admin.result');
        $participants = $event->getParticipants();
        $resultType = $event->getResultType();
        $parentResults = [];

        foreach ($participants as $participant) {
            $result = $resultAdmin->createResult($resultType);
            $result->setParticipant($participant);
            $event->addResult($result);
            $parentResults[$participant->getId()] = $result;
        }
        $event->setIsStarted(true);

        if($resultType == Result::CUMULATIVE) {
            /** @var SectionAdmin $sectionAdmin */
            $sectionAdmin = $this->getConfigurationPool()->getAdminByAdminCode('admin.section');
            /** @var Section $section */
            $section = $event->getSections()->first();
            $sectionAdmin->startSectionForAllParticipants($section, $parentResults);
        }

        $em->flush($event);
    }


    public function getEditableSections() {
        /** @var Event $event */
        $event = $this->getSubject();
        /** @var Section[] $sections */
        $_sections = $event->getSections();
        $sections = [];
        foreach($_sections as $section) {
            if(!$section->isParent()) {
                $sections[] = $section;
            }
        }
        return $sections;

    }
}
