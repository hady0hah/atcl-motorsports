<?php


namespace App\Controller\Admin;

use App\Admin\EventAdmin;
use App\Entity\Event;
use App\Entity\Result;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EventAdminController extends DefaultAdminController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

//    private function getParticipants($eventParticipants)
//    {
//        $participants = [];
//        foreach ($eventParticipants as $eventParticipant)
//        {
//            $participants[] = $eventParticipant->getParticipant();
//
//        }
//
//        return $participants;
//    }

    private function stopAllStartedEvents()
    {
        $events = $this->em->getRepository(Event::class)->findBy(['isStarted' => true]);

        foreach ($events as $event)
        {
            $event->setIsStarted(false);
        }
    }


    public function startAction($id): RedirectResponse
    {
        /** @var Event $event */
        $event = $this->admin->getSubject();

        if (!$event) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id: %s', $id));
        }

        if (!$event->getParticipants() || count($event->getParticipants()) === 0 ){
            $this->addFlash('sonata_flash_error', "Event doesn't have participants");
            return new RedirectResponse($this->admin->generateUrl('list'));
        }

        if (!$event->getResults() || count($event->getResults()) <= 0) {

            $this->stopAllStartedEvents();
            /** @var EventAdmin $admin */
            $admin = $this->admin;
            $admin->startEvent();

            $this->addFlash('sonata_flash_success', 'Event "'. $event->getLabel() .'" started successfully');
        }
        else {
            $this->addFlash('sonata_flash_error', 'Event "'. $event->getLabel() . '" already started');

        }

        return new RedirectResponse($this->admin->generateUrl('list'));
    }

    public function stopEventsAction()
    {
        $query = "UPDATE " . Event::class . " e SET e.isStarted = 0";
        $this->em->createQuery($query)->execute();
        $this->addFlash('sonata_flash_success', 'All events stopped');
        return new RedirectResponse($this->admin->generateUrl('list'));
    }

    public function resultsAction() {
        $event = $this->admin->getSubject();
        
        return $this->renderWithExtraParams('Admin/event_results.html.twig',[
            'event' => $event,
            'admin' => $this->admin,
            'result_admin' => $this->admin->getConfigurationPool()->getAdminByAdminCode('admin.result')
        ]);
    }
}