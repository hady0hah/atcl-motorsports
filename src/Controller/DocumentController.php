<?php


namespace App\Controller;


use App\Entity\Document;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DocumentController extends DefaultController
{
    public function listAction(Request $request)
    {
        $limit = $request->query->get('limit', 10);

        $documents  = $this->entityManager->getRepository(Document::class)->findDocumentsLimited($limit);
        $context = $request->get('_context');
        $groups = ['groups' => $context];
        $data = $this->serializeObject($documents, $groups);

        $response = ["status" => 200, "success" => true, "data" => $data];
        return new JsonResponse($response, Response::HTTP_OK);
    }
}