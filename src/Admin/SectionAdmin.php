<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Participant;
use App\Entity\Result;
use App\Entity\Section;
use App\Utilities\NameGenerator;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\DoctrineORMAdminBundle\Model\ModelManager;
use Sonata\Form\Type\DateTimePickerType;
use Sonata\Form\Validator\ErrorElement;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Vich\UploaderBundle\Form\Type\VichImageType;


final class SectionAdmin extends AbstractAdmin
{
    protected $datagridValues = [
        '_sort_order' => 'ASC',
        '_sort_by' => 'ordering'
    ];

    private function orderingValidation($errorElement, $object)
    {
        $order = $object->getOrdering();
        $sections = $this->getDatagrid()->getResults();

        if ($order <= 0)
            $errorElement->addViolation('Section order should be minimum 1');

        foreach ($sections as $section) {
            if ($section->getOrdering() === $order && $this->getSubject()->getId() !== $object->getId())
                $errorElement->addViolation('Section order already exists');
        }
    }

    private function sectionTimeValidation(ErrorElement $errorElement, $object)
    {
        if ($object->getEndDate() && $object->getStartDate() > $object->getEndDate())
            $errorElement->addViolation("Section date is invalid");
    }


    public function validate(ErrorElement $errorElement, $object)
    {
        $this->orderingValidation($errorElement, $object);
        $this->sectionTimeValidation($errorElement, $object);
    }

    protected function configureRoutes(RouteCollection $collection)
    {


        $collection
            ->add('result', $this->getRouterIdParameter() . '/result/list')
            ->add('start', $this->getRouterIdParameter() . '/start');

        //        if ($this->getCode() === 'admin.section')
        //            $collection->add('parentSection', $this->getRouterIdParameter() . '/section/list');

        //        if ($this->getParent()->getSubject() instanceof Event && $this instanceof SectionAdmin)
        //            $collection->remove('edit');

    }

    //    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    //    {
    //        $datagridMapper
    ////            ->add('event')
    ////            ->add('parentSection');
    //    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $actions = [
            'start' => ['template' => 'Admin\start_event_button.html.twig', 'route' => 'start'],
            'edit' => [],
            'delete' => [],
            'section' => ['template' => 'Admin\section_button.html.twig'],
            //                'parentSection' => ['template' => 'list_button.html.twig', 'route' => 'parentSection', 'label' => 'Sections', 'icon' => 'fa fa-plus'],
            'result' => ['template' => 'list_button.html.twig', 'route' => 'result', 'label' => 'Results', 'icon' => 'fa fa-th-list'],
        ];

        //        if ($this->getCode() === 'admin.section')
        //            $actions['parentSection'] = ['template' => 'list_button.html.twig', 'route' => 'parentSection', 'label' => 'Sections', 'icon' => 'fa fa-plus'];


        $listMapper
            //            ->add('id')
            //            ->add('name')
            ->add('label')
            ->add('startDate')
            ->add('endDate')
            ->add('location')
            ->add('description')
            ->add('_action', null, [
                "actions" => $actions
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $order = 0;
        $sections = $this->getDatagrid()->getResults();

        if (count($sections) > 0)
            $order = end($sections)->getOrdering();

        $section = $this->getSubject();
        //        $event = Section::getEventSection($section);
        $isDisabled = false;

        if (($section->getIsStarted() || $section->isOver()) && $section->getId() !== null)
            $isDisabled = true;

        $orderingOptions = ['disabled' => $isDisabled, 'label' => 'Order',];
        if (!$this->getSubject()->getId()) {
            $orderingOptions['attr'] = [
                'value' => ++$order
            ];
        }

        $formMapper
            ->tab('Section')
            ->with('Section')
            ->add(
                'bannerFile',
                VichImageType::class,
                [
                    'disabled' => $isDisabled,
                    'required' => false,
                    'label' => 'Banner',
                ]
            )
            ->add('ordering', NumberType::class, $orderingOptions)
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
            // ->add('endDate', DateTimePickerType::class, [
            //     'format' => "yyyy-MM-dd HH:mm:ss",
            //     'disabled' => $isDisabled,
            // ])
            ->add('targetTime', null, [
                'disabled' => $isDisabled,
            ])
            ->add('location', null, [
//                'disabled' => $isDisabled,
            ])
            ->add('description', null, [
//                'disabled' => $isDisabled,
            ])
            ->add('isIncludedInFinalResult', null, [
                'disabled' => $isDisabled,
                'label' => 'Include In Final Result'
            ]);
        if ($this->getEvent()->getResultType() == Result::CUMULATIVE) {
            $formMapper
                ->add('sectionType', ChoiceType::class, [
                    'disabled' => $isDisabled,
                    'choices' => [
                        'Leg' => Section::LEG,
                        'Section' => Section::SECTION,
                        'TC' => Section::TC,
                        'SS' => Section::SS
                    ]
                ]);
        }
        $formMapper
            ->end()
            ->end()
            //            ->tab('Subsections')
            //            ->with('Subsections')
            //            ->add('childrenSections', CollectionType::class, [
            //                'entry_type' => SectionEventFormType::class,
            //                'allow_add' => true,
            //                'allow_delete' => true,
            //                'label' => 'Add Sub Section'
            //            ], [
            //                'edit' => 'inline',
            //                'inline' => 'table',
            //                'sortable' => 'position'
            //            ])
            //            ->end()
            //            ->end()
            ////            ->add('resultType', ChoiceType::class,[
            ////                'choices' => [
            ////                    'Points' => 'point',
            ////                    'Start Time | End Time' => 'startend',
            ////                    'Timer' => 'timer'
            ////                ]
            ////            ])
        ;
    }

    public function prePersist($object)
    {
        $this->setObjectProperties($object);

        if ($object->getParentSection() && !$object->getParentSection()->getIsParent()) {
            $object->getParentSection()->setIsParent(true);
            /** @var ModelManager $em */
            $em = $this->getModelManager();
            $em->getEntityManager($object)->persist($object->getParentSection());
        }
    }

    public function preUpdate($object)
    {
        $this->setObjectProperties($object);

        if (!$object->getChildrenSections()->isEmpty()) {
            $object->setIsParent(true);
            foreach ($object->getChildrenSections() as $section) {
                $section->setParentSection($object);
            }
        }
    }

    private function setObjectProperties(Section $object)
    {
        NameGenerator::defaultSave($object);
    }

    private function getEvent()
    {
        if ($ev = $this->getSubject()->getEvent()) {
            return $ev;
        } else {
            return $this->getSubject()->getParentSection()->getEvent();
        }
    }

    public function startSectionForAllParticipants(Section $section=NULL, $parentResults=[])
    {
        if(!$section) $section = $this->getSubject();
        if(!$section) return false;
        if ($section->getResults() && count($section->getResults()) > 0) {
            return true;
        }
        
        /** @var ModelManager $em */
        $em = $this->getModelManager();
        $em = $em->getEntityManager($section);
        
        /** @var ResultAdmin $resultAdmin */
        $resultAdmin = $this->getConfigurationPool()->getAdminByAdminCode('admin.result');
        $event = $section->getEvent();

        /** @var Participant[] $participants */
        $participants = $em->getRepository(Participant::class)->findNotDnfEventParticipants($event->getId());
        $resultType = $section->getResultType();
        $_parentResults=[];

        if($section->getExpectedStartDate()) {
            $prevExpectedStartDate = $section->getExpectedStartDate();
        }
        $expectedStartDate = NULL;
        foreach ($participants as $participant) {
            if($section->getExpectedStartDate()) {
                $prevExpectedStartDate->add(new \DateInterval('PT' . ($participant->getGap()?:0) . 'M'));
                $expectedStartDate = clone $prevExpectedStartDate;
            }
            $parentResult = null;
            if(isset($parentResults[$participant->getId()])) {
                $parentResult = $parentResults[$participant->getId()];
            }
            $result = $resultAdmin->createResultForParticipant(
                $section,
                $participant,
                $resultType,
                $parentResult,
                $expectedStartDate,
                $resultAdmin
            );
            $_parentResults[$participant->getId()] = $result;
        }
        $section->setIsStarted(true);
        $em->flush($section);
        if($section->isParent()) {
            $_section = $section->getChildrenSections()->first();
            $this->startSectionForAllParticipants($_section, $_parentResults);
        }
    }

    public function getNextSection(Section $section) {
        if(!$section->getParentSection()) return;
        
        $siblings = $section->getParentSection()->getChildrenSections();
        $nextIndex = $siblings->indexOf($section) + 1;

        if($nextIndex >= $siblings->count()) {
            $nextSection = $this->getNextSection($section->getParentSection());
            if(!$nextSection) return;
            if($nextSection->getSectionType() == Section::LEG) return;
        } else {
            $nextSection = $siblings[$nextIndex];
        }

        return $nextSection;
    }
}
