<?php


namespace App\Controller;


use App\Entity\Section;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SectionController extends DefaultController
{
    public function showAction(Request $request, $id)
    {
        $section = $this->entityManager->getRepository(Section::class)->find($id);


        if (!$section){
            $status = Response::HTTP_NOT_FOUND;
            $response = ["status" => $status, "success" => false, "errors" => "Section not found"];
            return new JsonResponse($response, $status);
        }

        $context = $request->get('_context');

        $groups = ['groups' => $context];

        $data = $this->serializeObject($section, $groups);

        $response = ['status' => Response::HTTP_OK, "success" => true, "data" => $data];

        return new JsonResponse($response, Response::HTTP_OK);
    }

}