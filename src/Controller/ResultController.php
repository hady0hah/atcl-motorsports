<?php


namespace App\Controller;


use App\Admin\ResultAdmin;
use App\Entity\Event;
use App\Entity\Participant;
use App\Entity\Result;
use App\Entity\Section;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResultController extends DefaultController
{

    public function listEventResultAction(Request $request, $id)
    {
        $event = $this->entityManager->getRepository(Event::class)->find($id);
        if (!$event){
            $status = Response::HTTP_NOT_FOUND;
            $response = ["status" => $status, "success" => false, "errors" => "Event not found"];

            return new JsonResponse($response, $status);
        }

        $results = $this->entityManager->getRepository(Result::class)->findResultsByEvent($event);

        $context = $request->get('_context');

        $groups = ['groups' => $context];

        $data = $this->serializeObject($results, $groups);

        $response = ['status' => Response::HTTP_OK, "success" => true,"count" => count($results), "resultType" => $event->getResultType(),"data" => $data];
        return new JsonResponse($response, Response::HTTP_OK);
    }

    public function listRunningEventResult(Request $request)
    {
        $event = $this->entityManager->getRepository(Event::class)->findOneBy(['isStarted' => true]);
        $results = $this->entityManager->getRepository(Result::class)->findResultsByEvent($event);


        $context = $request->get('_context');

        $groups = ['groups' => $context];

        $data = $this->serializeObject($results, $groups);

        $response = ['status' => Response::HTTP_OK, "success" => true, "count" => count($results), "data" => $data];

        return new JsonResponse($response, Response::HTTP_OK);
    }

    public function listSectionResult(Request $request, $id)
    {
        $section = $this->entityManager->getRepository(Section::class)->find($id);

        if (!$section)
        {
            $status = Response::HTTP_NOT_FOUND;
            $response = ["status" => $status, "success" => false, "errors" => "Section not found"];
            return new JsonResponse($response, $status);
        }
        $results = $this->entityManager->getRepository(Result::class)->findResultsBySection($section);
//        $results = $section->getResults();

        $context = $request->get('_context');

        $groups = ['groups' => $context];

        $data = $this->serializeObject($results, $groups);

        $response = ['status' => Response::HTTP_OK, "success" => true, "count" => count($results), "data" => $data];

        return new JsonResponse($response, Response::HTTP_OK);
    }


}