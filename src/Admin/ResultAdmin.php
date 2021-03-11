<?php

namespace App\Admin;

use App\Entity\Event;
use App\Entity\Participant;
use App\Entity\Result;
use App\Entity\ResultCheckpoint;
use App\Entity\ResultCumulative;
use App\Entity\ResultPoint;
use App\Entity\ResultStartEnd;
use App\Entity\ResultTime;
use App\Entity\Section;
use App\Form\MillisecondTimeType;
use App\Utilities\NameGenerator;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\Form\Validator\ErrorElement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface as RoutingUrlGeneratorInterface;

class ResultAdmin extends AbstractAdmin
{

    protected $editFormView;
    protected $objectId;

    // public function getEditFormViewVar()
    // {
    //     return $this->editFormView;
    // }

    public function getListEditForm($objectId)
    {
        if ($objectId != $this->objectId) {
            $form = $this->getForm();
            $object = $this->getObject($objectId);
            $this->setSubject($object);
            $form->setData($object);
            $this->configureListEditFormFields($form);
            $this->editFormView = $form->createView();
        }

        return $this->editFormView;
    }

    public function resultPointFormFields($formMapper)
    {
        $formMapper
            ->add('value', NumberType::class, [
                'required' => false,
                'label' => 'Score'
            ])
            ->add('penalty', NumberType::class)
            ->add('valueNumber', NumberType::class, [
                'label' => 'Final Result',
                'required' => false
            ]);
    }

    public function resultStartEndFormFields($form)
    {
        $form
            ->add('expectedStartDate', DateTimeType::class, [
                'with_seconds' => true,
                'label' => 'Start Date and Time',
                // 'input' => 'string',
                'widget' => 'single_text'
            ])
            ->add('startDate', TimeType::class, [
                'with_seconds' => true,
                'label' => 'Start Date and Time',
                'input' => 'string',
                'widget' => 'single_text'
            ])
            ->add('endDate', MillisecondTimeType::class, [
                'required' => false,
                'with_milliseconds' => true,
                'label' => 'End Date and Time',
                'input' => 'string',
                'widget' => 'single_text'
            ])
            ->add('penalty')
            // ->add('valueNumber', TextType::class, [
            //     'label' => 'Final Result',
            //     'required' => false
            // ])
        ;
        // $form->get('valueNumber')
        //     ->addModelTransformer(new CallbackTransformer(
        //         function ($valueNumberAsFloat) {
        //             return ResultStartEnd::epochToTimer($valueNumberAsFloat);
        //         },
        //         function ($valueNumberAsTimer) {
        //             return ResultStartEnd::timerToEpoch($valueNumberAsTimer);
        //         }
        //     ));
    }

    public function resultTimeFormFields($form)
    {
        $form
            ->add('value', TextType::class, [
                'label' => 'Result Time',
                'required' => false,
                'attr' => [
                    'pattern' => '^([0-9]{1}[0-9]{1}[0-9]{1}|[0-9]{1}[0-9]{1}|[0-9]{1}):[0-5]{1}[0-9]{1}:[0-5]{1}[0-9]{1}:([0-9]{1}[0-9]{1}[0-9]{1}|[0-9]{1}[0-9]{1}|[0-9]{1})$',
                    'placeholder' => 'hours:minutes:seconds:milliseconds',
                    'title' => 'The input should be of the form hhh:mm:ss:uuu',
                ]
            ])
            ->add('penalty')
            ->add('valueNumber', TextType::class, [
                'required' => false
            ]);
//        $form->get('valueNumber')
//            ->addModelTransformer(new CallbackTransformer(
//                function ($valueNumberAsFloat) {
//                    return ResultTime::msToTimer($valueNumberAsFloat);
//                },
//                function ($valueNumberAsTimer) {
//                    return  $valueNumberAsTimer;
////                    return ResultTime::timerToMs($valueNumberAsTimer);
//                }
//            ));
    }

    public function resultCheckpointFormFields($form)
    {
        $form
            ->add('expectedStartDate', DateTimeType::class, [
                'with_seconds' => true,
                'label' => 'Provisional Time',
                // 'input' => 'string',
                'widget' => 'single_text',
            ])
            ->add('startDate', DateTimeType::class, [
                'with_seconds' => true,
                'label' => 'Actual Time',
                // 'input' => 'string',
                'widget' => 'single_text',
                'attr'=>[
                    'class' => 'tc_start_date',
                ]
            ]);
    }

    public function getTemplate($name)
    {
        if (in_array($name, ['layout', 'list']) && $this->getRequest()->get('_layout') == 'empty') {
            if ($name == 'layout') return '@SonataAdmin/empty_layout.html.twig';
            if ($name == 'list') return 'Admin/list_only.html.twig';
        }
        return parent::getTemplate($name);
    }


    public function validate(ErrorElement $errorElement, $object)
    {
        if ($object->getType() === Result::STARTEND) {
            if ($object->getStartDate() > $object->getEndDate())
                $errorElement->addViolation("Invalid result date");
        }
    }

    public function configureRoutes(RouteCollection $collection)
    {
        $collection->add('reset_dnf', $this->getRouterIdParameter() . '/reset_dnf');
        $collection->remove('create');
        $collection->remove('batch');
    }

    public function configureActionButtons($action, $object = null)
    {
        $buttons = parent::configureActionButtons($action, $object); // TODO: Change the autogenerated stub
        //        if (in_array($action, array('list'))) {
        unset($buttons['create']);
        //        }

        return $buttons;
    }

    protected function configureBatchActions($actions)
    {
        unset($actions['delete']);
        return $actions;
    }


    protected function configureListFields(ListMapper $list)
    {
        $parent = $this->getParent();
        if($parent) {
            $parent = $parent->getSubject();
            $resultType = $parent->getResultType();
        }else{
            $resultType = null;
        }

        $hasChild = ($parent instanceof Event && $parent->getSections()) || ($parent instanceof Section && $parent->getChildrenSections());
        $list
            // ->add('id')
            ->add('participant.number')
            ->add('participant', 'text', [])
            //            ->add('name', 'edit', [
            //                'label' => "Result Code",
            //
            //            ])
            // ->add('label', null, [
            //     'label' => "Result Label",

            // ])
            ->add('dnf', 'edit')
            ->add('published', null, ['editable'=>true]);



        if ($resultType == 'startend') {
            $list->add('expectedStartDate', 'edit', ['label' => 'Provisional Start']);
            $list->add('startDate', 'edit', ['label' => 'Actual Start'])
                ->add('endDate', 'edit');
        } elseif ($resultType == 'checkpoint') {
            $list->add('expectedStartDate', 'edit', ['label' => 'Provisional Time']);
            $list->add('startDate', 'edit', ['label' => 'Actual Time']);
        } else {
            $list->add('value', 'edit', [
                'label' => 'Result',
            ]);
        }
        $list
            ->add('penalty', 'edit');
        if ($hasChild) {
            $list->add('valueNumber', 'text', [
                'template' => 'Admin/final_result_value.html.twig',
                'label' => 'Final Result'
            ]);
        }
        $list->add('_action', null, [
            'actions' => [
                'save' => ['template' => 'Admin/save_form_list.html.twig'],
                'reset' => [
                    'template' => 'Admin/reset_dnf_button.html.twig',
                    'route' => 'reset',
                    'label' => 'Reset Dnf',
                    'icon' => 'fa fa-refresh'
                ],
                //                'delete' => [],
            ],
        ]);
    }
    protected function configureFormFields(FormMapper $form)
    {
        $parent = $this->getParent()->getSubject();
        $resultType = $parent->getResultType();
        $hasChild = ($parent instanceof Event && $parent->getSections()) || ($parent instanceof Section && $parent->getChildrenSections());

        $form
            ->add('participant', EntityType::class, [
                'class' => Participant::class,
                'disabled' => true
            ])
            ->add('label', null, [
                'label' => 'Result Label',
                'disabled' => true
            ])
            ->add('dnf')
            ->add('penalty');


        if ($resultType == Result::POINT)
            $this->resultPointFormFields($form);

        else if ($resultType == Result::STARTEND)
            $this->resultStartEndFormFields($form);

        else if ($resultType == Result::TIMER)
            $this->resultTimeFormFields($form);

        else if ($resultType == Result::CHECKPOINT)
            $this->resultCheckpointFormFields($form);

        if (!$hasChild)
            $form->remove('valueNumber');
    }

    protected function configureListEditFormFields($form)
    {
        $parent = $this->getParent()->getSubject();
        $resultType = $parent->getResultType();
        if ($resultType == Result::POINT)
            $this->resultPointListEditFormFields($form);

        else if ($resultType == Result::STARTEND)
            $this->resultStartEndListEditFormFields($form);

        else if ($resultType == Result::TIMER)
            $this->resultTimeListEditFormFields($form);

        else if ($resultType == Result::CHECKPOINT)
            $this->resultCheckpointListEditFormFields($form);
    }

    private function resultPointListEditFormFields($form)
    {
    }
    private function resultStartEndListEditFormFields($form)
    {
    }
    private function resultTimeListEditFormFields($form)
    {
    }
    private function resultCheckpointListEditFormFields($form)
    {
        $subject = $this->getSubject();
        if (!$subject->getStartDate() && $subject->getExpectedStartDate()) {
            $subject->setStartDate($subject->getExpectedStartDate());
        }
    }

    public function setDnfResultRecursively($result, $dnf = true)
    {
        if ($section = $result->getSection()) {
            if ($parent = $section->getEvent()) {
                /** @var ModelManager $em */
                $em = $this->getModelManager();
                $result = $em->getEntityManager(Result::class)
                    ->getRepository(Result::class)->findOneBy(['event' => $parent, 'participant' => $result->getParticipant()]);
                $result->setDnf($dnf);
            } else {
                $parent = $section->getParentSection();
                /** @var ModelManager $em */
                $em = $this->getModelManager();
                $result = $em->getEntityManager(Result::class)
                    ->getRepository(Result::class)->findOneBy(['section' => $parent, 'participant' => $result->getParticipant()]);
                $result->setDnf($dnf);
                return $this->setDnfResultRecursively($result, $dnf);
            }
        }
    }
    public function prePersist($result)
    {
        $this->setNameLabel($result);
    }

    public function preUpdate($result)
    {
        $this->setNameLabel($result);

        if ($result->getDnf()) {
            $result->getParticipant()->setDnf(true);
            $result->setValueNumberDnf();
            $this->setDnfResultRecursively($result);
        } else {
            $result->calculateResult();
            // start next section

            /** @var ModelManager $em */
            $em = $this->getModelManager();
            $em = $em->getEntityManager($result->getSection());

            /** @var SectionAdmin $sectionAdmin */
            $sectionAdmin = $this->getConfigurationPool()->getAdminByAdminCode('admin.section');
            $nextSection = $sectionAdmin->getNextSection($result->getSection());
            if($nextSection) {
                $participant = $result->getParticipant();
                $nextSectionResult = $em->getRepository(Result::class)->findOneBy(['section'=>$nextSection, 'participant'=>$participant]);
                if(!$nextSectionResult) {
                    if($nextSection->getParentSection()) {
                        $nextSectionParent = $nextSection->getParentSection();
                        $parentResult = $em->getRepository(Result::class)->findOneBy(['section'=>$nextSectionParent, 'participant'=>$participant]);
                    } else {
                        $parentResult = NULL;
                    }
                
                    while($nextSection->isParent()) {
                        $resultType = $nextSection->getResultType();
                        $parentResult = $this->createResultForParticipant($nextSection, $participant, $resultType, $parentResult);
                        $nextSection = $nextSection->getChildrenSections()->first();
                        $em->persist($nextSection);
                    }

                    /** @var DateTime $expectedStartDate */
                    $expectedStartDate = clone $result->getExpectedStartDate();
                    $expectedStartDate->add(new \DateInterval('PT' . ($nextSection->getTargetTime()?:0) . 'M'));
                    $resultType = $nextSection->getResultType();
                    $this->createResultForParticipant($nextSection, $participant, $resultType, $parentResult, $expectedStartDate);
                    $em->persist($nextSection);
                }
            }
        }
        // dump($result);
        // die;
    }

    public function createResultForParticipant(
        Section &$section, 
        Participant &$participant, 
        $resultType, 
        Result $parentResult=NULL, 
        $expectedStartDate=NULL
    ) {
        $result = $this->createResult($resultType);
        $result->setParticipant($participant);
        if(method_exists($result, 'setExpectedStartDate'))
            $result->setExpectedStartDate($expectedStartDate);
        $result->setParentResult($parentResult);
        $result->setSection($section);
        $section->addResult($result);
        $result->setIsIncludedInFinalResult($section->getIsIncludedInFinalResult());

        return $result;
    }

    private function setNameLabel($result)
    {
        if (!$result->getLabel()) {
            $result->setLabel($result->getSection()->getLabel());
            NameGenerator::defaultSave($result);
        }
    }

    //    public function getResultClassName($result): string
    //    {
    //        if ($result instanceof ResultPoint)
    //            return self::POINT;
    //
    //        if ($result instanceof ResultStartEnd)
    //            return self::STARTEND;
    //
    //        if ($result instanceof ResultTime)
    //            return self::TIMER;
    //    }

    public function calculateStartEndResult($em, $participantId, $parentNode)
    {
        //        $selectQuery = "SELECT s.ordering FROM ". Section::class . " s
        //                        INNER JOIN s.results r
        //                        WHERE (s.ordering = (
        //                            SELECT MAX(s1.ordering)
        //                            FROM " . Section::class . " s1
        //                            WHERE s1.event = :eventId)
        //                        OR s.ordering = (
        //                            SELECT MIN(s2.ordering)
        //                            FROM ". Section::class ." s2
        //                            WHERE s2.event = :eventId))
        //                        AND s.event = :eventId and r.participant = :participantId";

        if ($parentNode instanceof Event) {
            $selectQuery = "SELECT r FROM " . ResultStartEnd::class . " r
                            INNER JOIN r.section s
                            WHERE s.event = :eventId and r.participant = :participantId
                            ORDER BY s.ordering";

            $resultQuery = $em->createQuery($selectQuery)
                ->setParameter('eventId', $parentNode->getId())
                ->setParameter('participantId', $participantId)
                ->getResult();

            $start = $resultQuery[0]->getStartDate();
            $end = end($resultQuery)->getEndDate();
            $res = implode(",", [$start, $end]);

            $q = "SELECT SUM(r2.valueNumber) as sumValueNum
                        FROM " . Section::class . " s2
                        INNER JOIN s2.results r2
                        WHERE s2.event = :eventId AND r2.participant = :participantId AND s2.isIncludedInFinalResult = 1";


            $q = $em->createQuery($q)
                ->setParameter('eventId', $parentNode->getId())
                ->setParameter('participantId', $participantId)
                ->getResult();


            $query = "UPDATE " . ResultStartEnd::class . " re SET re.value = :result,
                      re.valueNumber = (" . $q[0]['sumValueNum'] . ")
                        WHERE re.event = :eventId AND re.participant = :participantId";

            $resultQuery = $em->createQuery($query)
                ->setParameter('result', $res)
                ->setParameter('eventId', $parentNode->getId())
                ->setParameter('participantId', $participantId)
                ->execute();
        } else {
            $selectQuery = "SELECT r FROM " . ResultStartEnd::class . " r
                            INNER JOIN r.section s
                            WHERE s.parentSection = :eventId and r.participant = :participantId
                            ORDER BY s.ordering";

            $resultQuery = $em->createQuery($selectQuery)
                ->setParameter('eventId', $parentNode->getId())
                ->setParameter('participantId', $participantId)
                ->getResult();

            $start = $resultQuery[0]->getStartDate();
            $end = end($resultQuery)->getEndDate();
            $res = implode(",", [$start, $end]);

            $q = "SELECT SUM(r2.valueNumber) as sumValueNum
                        FROM " . Section::class . " s2
                        INNER JOIN s2.results r2
                        WHERE s2.parentSection = :eventId AND r2.participant = :participantId AND s2.isIncludedInFinalResult = 1";

            $q = $em->createQuery($q)
                ->setParameter('eventId', $parentNode->getId())
                ->setParameter('participantId', $participantId)
                ->getResult();

            $query = "UPDATE " . ResultStartEnd::class . " re SET re.value = :result,
                      re.valueNumber = (" . $q[0]["sumValueNum"] . ")
                        WHERE re.section = :eventId AND re.participant = :participantId";

            $resultQuery = $em->createQuery($query)
                ->setParameter('result', $res)
                ->setParameter('eventId', $parentNode->getId())
                ->setParameter('participantId', $participantId)
                ->execute();

            return $this->calculateStartEndResult($em, $participantId, Section::getParentNode($parentNode));
        }
    }

    public function calculateTimerResult($em, $participantId, $parentNode,$result)
    {
        if (!$result->getDnf()) {
            if ($parentNode instanceof Event) {
                $selectQuery = "SELECT SUM(r1.valueNumber) as sumValue
                        FROM " . Section::class . " s1
                        INNER JOIN s1.results r1
                        WHERE s1.event = :eventId AND r1.participant = :participantId";

                $resultQuery = $em->createQuery($selectQuery)
                    ->setParameter('eventId', $parentNode->getId())
                    ->setParameter('participantId', $participantId)
                    ->getResult();

                $timerSum = ResultTime::msToTimer($resultQuery[0]['sumValue']);

                $q = "SELECT SUM(r2.valueNumber) as sumValueNum
                        FROM " . Section::class . " s2
                        INNER JOIN s2.results r2
                        WHERE s2.event = :eventId AND r2.participant = :participantId AND s2.isIncludedInFinalResult = 1";


                $q = $em->createQuery($q)
                    ->setParameter('eventId', $parentNode->getId())
                    ->setParameter('participantId', $participantId)
                    ->getResult();
                $timeInMicroSeconds = Result::timerToMs($result->getValue());
                $timeFloatInMinutes = $timeInMicroSeconds / 1000;
                $timeFloatInMinutes = $timeFloatInMinutes + $result->getPenalty();
                $query = "UPDATE " . ResultTime::class . " re SET re.value = :timerSum,
                        re.valueNumber = (:valueNumber)
                          WHERE re.id = :resultId";
                $em->createQuery($query)
                    ->setParameter('valueNumber', $timeFloatInMinutes)
//                ->setParameter('eventId', $parentNode->getId())
//                ->setParameter('participantId', $participantId)
                    ->setParameter('resultId', $result->getId())
                    ->setParameter('timerSum', $result->getValue())
                    ->execute();
            } else {
                $selectQuery = "SELECT SUM(r1.valueNumber) as sumValue
                        FROM " . Section::class . " s1
                        INNER JOIN s1.results r1
                        WHERE s1.parentSection = :eventId AND r1.participant = :participantId";

                $resultQuery = $em->createQuery($selectQuery)
                    ->setParameter('eventId', $parentNode->getId())
                    ->setParameter('participantId', $participantId)
                    ->getResult();

                $timerSum = ResultTime::msToTimer($resultQuery[0]['sumValue']);

                $q = "SELECT SUM(r2.valueNumber) as sumValueNum
                        FROM " . Section::class . " s2
                        INNER JOIN s2.results r2
                        WHERE s2.parentSection = :eventId AND r2.participant = :participantId AND s2.isIncludedInFinalResult = 1";

                $q = $em->createQuery($q)
                    ->setParameter('eventId', $parentNode->getId())
                    ->setParameter('participantId', $participantId)
                    ->getResult();

                $query = "UPDATE " . ResultTime::class . " re SET re.value = :timerSum,
                        re.valueNumber = (" . $q[0]["sumValueNum"] . ")
                        WHERE re.section = :eventId AND re.participant = :participantId";

                $em->createQuery($query)
                    ->setParameter('eventId', $parentNode->getId())
                    ->setParameter('participantId', $participantId)
                    ->setParameter('timerSum', $timerSum)
                    ->execute();

                return $this->calculateTimerResult($em, $participantId, Section::getParentNode($parentNode));
            }
        }
        return true;
    }

    public function calculatePointResult($em, $participantId, $parentNode)
    {

        if ($parentNode instanceof Event) {

            $q1 = "SELECT SUM(r1.valueNumber) as sumValue
                        FROM " . Section::class . " s1
                        INNER JOIN s1.results r1
                        WHERE s1.event = :eventId AND r1.participant = :participantId";

            $q1 = $em->createQuery($q1)
                ->setParameter('eventId', $parentNode->getId())
                ->setParameter('participantId', $participantId)
                ->getResult();

            $q2 = "SELECT SUM(r2.valueNumber) as sumValueNum
                        FROM " . Section::class . " s2
                        INNER JOIN s2.results r2
                        WHERE s2.event = :eventId AND r2.participant = :participantId AND s2.isIncludedInFinalResult = 1";

            $q2 = $em->createQuery($q2)
                ->setParameter('eventId', $parentNode->getId())
                ->setParameter('participantId', $participantId)
                ->getResult();
            $query = "UPDATE " . ResultPoint::class . " re SET re.value = (:value),
                        re.valueNumber = (:valueNumber)
                        WHERE re.event = :eventId AND re.participant = :participantId";
        } else {

            $q1 = "SELECT SUM(r1.valueNumber) as sumValue
                        FROM " . Section::class . " s1
                        INNER JOIN s1.results r1
                        WHERE s1.parentSection = :parentId AND r1.participant = :participantId";


            $q1 = $em->createQuery($q1)
                ->setParameter('parentId', $parentNode->getId())
                ->setParameter('participantId', $participantId)
                ->getResult();

            $q2 = "SELECT SUM(r2.valueNumber) as sumValueNum
                        FROM " . Section::class . " s2
                        INNER JOIN s2.results r2
                        WHERE s2.parentSection = :parentId AND r2.participant = :participantId AND s2.isIncludedInFinalResult = 1";

            $q2 = $em->createQuery($q2)
                ->setParameter('parentId', $parentNode->getId())
                ->setParameter('participantId', $participantId)
                ->getResult();

            $query = "UPDATE " . ResultPoint::class . " re SET re.value = (:value),
                        re.valueNumber = (:valueNumber)
                        WHERE re.section = :parentId AND re.participant = :participantId";



            $em->createQuery($query)
                ->setParameter('value', $q1[0]["sumValue"])
                ->setParameter('valueNumber', $q2[0]["sumValueNum"])
                ->setParameter('parentId', $parentNode->getId())
                ->setParameter('participantId', $participantId)
                ->execute();


            return $this->calculatePointResult($em, $participantId, Section::getParentNode($parentNode));
        }

        //        $query = "UPDATE " . ResultPoint::class . " re SET re.value = (
        //                    SELECT SUM(r1.valueNumber) as sumValue
        //                    FROM " . Section::class . " s1
        //                    INNER JOIN s1.results r1
        //                    WHERE s1.event = :eventId AND r1.participant = :participantId),
        //                    re.valueNumber = (SELECT SUM(r2.valueNumber) as sumValueNum
        //                    FROM " . Section::class . " s2
        //                    INNER JOIN s2.results r2
        //                    WHERE s2.event = :eventId AND r2.participant = :participantId AND s2.isIncludedInFinalResult = 1)
        //                    WHERE re.event = :eventId AND re.participant = :participantId";

        $em->createQuery($query)
            ->setParameter('value', $q1[0]["sumValue"])
            ->setParameter('valueNumber', $q2[0]["sumValueNum"])
            ->setParameter('eventId', $parentNode->getId())
            ->setParameter('participantId', $participantId)
            ->execute();
    }
    public function calculateResults($result)
    {
        $section = $result->getSection();
        //        $eventId = $section->getEvent()->getId();

        $parentNode = Section::getParentNode($section);
        $participantId = $result->getParticipant()->getId();
        /** @var ModelManager $em */
        $em = $this->getModelManager();
        $em = $em->getEntityManager(Result::class);

        if ($result->getType() === Result::POINT)
            $this->calculatePointResult($em, $participantId, $parentNode);

        if ($result->getType() === Result::TIMER)
            $this->calculateTimerResult($em, $participantId, $parentNode,$result);

        // if ($result->getType() === Result::STARTEND)
        //     $this->calculateStartEndResult($em,  $participantId, $parentNode);
    }



    public function postUpdate($result)
    {
        $section = $result->getSection();

        if ($section && count($section->getChildrenSections()) == 0) {
            $this->calculateResults($result);
        }
    }

    public function createResult($resultType)
    {
        switch ($resultType) {
            case Result::POINT:
                return new ResultPoint();
            case Result::STARTEND:
                return new ResultStartEnd();
            case Result::CHECKPOINT:
                return new ResultCheckpoint();
            case Result::TIMER:
                return new ResultTime();
            case Result::CUMULATIVE:
                return new ResultCumulative();
        }
    }

    public function getResultAdmin()
    {
        $resultAdmin = $this->getConfigurationPool()->getAdminByAdminCode("admin.result");
        return $resultAdmin;
    }

    public function resetDnf()
    {
        $container = $this->getConfigurationPool()->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        $object = $this->getSubject();
        $this->_resetDnf($object);
        $em->flush();
    }
    private function _resetDnf($object)
    {
        $parent = $object->getParentResult();
        $object->setDnf(false);
        if ($parent) {
            $this->_resetDnf($parent);
        }
    }

    public function generateObjectUrl($name, $object, array $parameters = [], $referenceType = RoutingUrlGeneratorInterface::ABSOLUTE_PATH)
    {
        if($object->getSection()->getEvent()->getResultType() == Result::CUMULATIVE) {
            $eventAdmin = $this->getConfigurationPool()->getAdminByAdminCode('admin.event');
            return $eventAdmin->generateObjectUrl('results', $object->getSection()->getEvent(), $parameters, $referenceType);
        }
        return parent::generateObjectUrl($name, $object, $parameters, $referenceType);
    }
}
