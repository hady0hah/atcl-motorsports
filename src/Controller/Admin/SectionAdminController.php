<?php


namespace App\Controller\Admin;

use App\Admin\SectionAdmin;
use App\Entity\Participant;
use App\Entity\Result;
use App\Entity\Section;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SectionAdminController extends DefaultAdminController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


//    private function getEventSection($section)
//    {
//        if (!$section->getParentSection()) {
//            return $section->getEvent();
//        } else {
//            return $this->getEventSection($section->getParentSection());
//        }
//    }

    private function parentStarted($section)
    {
        if (!$section->getParentSection()) {
            $arr = [
                'name' => $section->getEvent()->getLabel(),
                'started' => $section->getEvent()->getIsStarted(),
            ];
            return $arr;
//            return $section->getEvent()->getIsStarted();
        } else {
            if (!$section->getParentSection()->getIsStarted()) {
                $arr = [
                    'name' => $section->getParentSection()->getLabel(),
                    'started' => $section->getParentSection()->getIsStarted(),
                ];
                return $arr;
            } else {
                $ev = Section::getEventSection($section->getParentSection());
                $arr = [
                    'name' => $ev->getIsStarted(),
                    'started' => $ev->getLabel(),
                ];
//                return $this->getEventSection($section->getParentSection());
                return $arr;
            }
        }
    }

//    private function getParticipants()
//    {
//        $section = $this->admin->getSubject();
//        $event = Section::getEventSection($section);
//
//        return $this->em->getRepository(Participant::class)->findNotDnfEventParticipants($event->getId());
//    }


    public function startAction($id): RedirectResponse
    {
        $section = $this->admin->getSubject();
        $event = Section::getEventSection($section);

        // $parentStarted = $this->parentStarted($section);

        if (!$section) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id: %s', $id));
        }

        // if (!$parentStarted['started']) {
        //     $this->addFlash('sonata_flash_error', '"' . $parentStarted['name'] . '" is not started');
        //     return new RedirectResponse($this->admin->generateUrl('list'));
        // }


        if (!$section->getResults() || count($section->getResults()) <= 0) {

            /** @var SectionAdmin $admin */
            $admin = $this->admin;

            $participants = $event->getParticipants();
            $q = "SELECT r FROM ". Result::class ." r WHERE r.participant in (:participants) AND r.event = :event";
            $results = $this->em
                ->createQuery($q)
                ->setParameter('participants',$participants)
                ->setParameter('event',$event)
                ->getResult();
            $parentResults = [];
            foreach($results as $result) {
                $parentResults[$result->getParticipant()->getId()] = $result;
            }

            $admin->startSectionForAllParticipants($section, $parentResults);

            $this->addFlash('sonata_flash_success', 'Section "' . $section->getLabel() . '" started successfully');
        }
        else {
            $this->addFlash('sonata_flash_error', 'Section "' . $section->getLabel() . '" already started');
        }
        return new RedirectResponse($this->admin->generateUrl('list'));
    }
}