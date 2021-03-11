<?php


namespace App\Controller;


use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EventController extends DefaultController
{

    private function isEventOver($event)
    {
        return $event->getEndDate() < new \DateTime('now');

    }

    public function listAction(Request $request)
    {
        $events = $this->entityManager->getRepository(Event::class)->findEvents();

        $context = $request->get('_context');
        $groups = ['groups' => $context];
        $response = $this->serializeObject($events, $groups);

        return new JsonResponse($response, Response::HTTP_OK);
    }
//    public function listAction(Request $request)
//    {
//        $events = $this->entityManager->getRepository(Event::class)->findEventsByChampionshipThisYear();
//
//        $perPage = $request->query->get('per_page', 1);
//        $page = $request->query->get('page', 1);
//        $events = $this->paginate($events, $perPage, $page);
//
//        $context = $request->get('_context');
//
//        $groups = ['groups' => $context];
//
//        $response = $this->serializeObject($events, $groups);
//
//        return new JsonResponse($response, Response::HTTP_OK);
//    }


    public function showAction(Request $request, $id)
    {
        $event = $this->entityManager->getRepository(Event::class)->find($id);

        if (!$event){
            $status = Response::HTTP_NOT_FOUND;
            $response = ["status" => $status, "success" => false, "errors" => "Event not found"];
            return new JsonResponse($response, $status);
        }

        $context = $request->get('_context');

        $groups = ['groups' => $context];

        $data = $this->serializeObject($event, $groups);


        $response = ['status' => Response::HTTP_OK, "success" => true, "data" => $data];

        return new JsonResponse($response, Response::HTTP_OK);

    }

    public function listTopBannerEventsAction(Request $request)
    {
        $events = $this->entityManager->getRepository(Event::class)->findTopBannerEvents();

//        $perPage = $request->query->get('per_page', 1);
//        $page = $request->query->get('page', 1);
//        $events = $this->paginate($events, $perPage, $page);

        $context = $request->get('_context');

        $groups = ['groups' => $context];

        $data = $this->serializeObject($events, $groups);

        $response = ['status' => Response::HTTP_OK, "success" => true,"count" => count($events), "data" => $data];
        return new JsonResponse($response, Response::HTTP_OK);
    }

    public function showUpcomingEventAction(Request $request)
    {
        $event = $this->entityManager->getRepository(Event::class)->findNearestEvent();

        if (!$event){ // if there is no upcoming events
            $status = Response::HTTP_NOT_FOUND;
            $response = ["status" => $status, "success" => false, "errors" => "Event not found"];
            return new JsonResponse($response, $status);
        }

        $context = $request->get('_context');

        $groups = ['groups' => $context];

        $data = $this->serializeObject($event, $groups);

        $response = ['status' => Response::HTTP_OK, "success" => true, "data" => $data];

        return new JsonResponse($response, Response::HTTP_OK);
    }

    public function listUpcomingEvents(Request $request)
    {

        $first = $request->query->get('first');
        $last = $request->query->get('last');
        $event = $this->entityManager->getRepository(Event::class)->findUpcomingEvents($first, $last);


//
//        $perPage = $request->query->get('per_page', 1);
//        $page = $request->query->get('page', 1);
//        $event = $this->paginate($event, $perPage, $page);

        $context = $request->get('_context');

        $groups = ['groups' => $context];

        $data = $this->serializeObject($event, $groups);

        $response = ['status' => Response::HTTP_OK, "success" => true, "data" => $data];

        return new JsonResponse($response, Response::HTTP_OK);
    }

    public function showRunningEventAction(Request $request)
    {
        $runningEvent = $this->entityManager->getRepository(Event::class)->findOneBy(['isStarted' => true]);

        $context = $request->get('_context');

        $groups = ['groups' => $context];

        $data = $this->serializeObject($runningEvent, $groups);

        $response = ['status' => Response::HTTP_OK, "success" => true, "data" => $data];

        return new JsonResponse($response, Response::HTTP_OK);
    }


}