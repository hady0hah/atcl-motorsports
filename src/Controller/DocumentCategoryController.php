<?php


namespace App\Controller;


use App\Entity\DocumentCategory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DocumentCategoryController extends DefaultController
{
    public function listAction(Request $request)
    {
        $documentCategories = $this->entityManager->getRepository(DocumentCategory::class)->findDocumentCategories();
        $count = $this->getCount($documentCategories);
        $perPage = $request->query->get('per_page', 32);
        $page = $request->query->get('page', 1);
        $documentCategories = $this->paginate($documentCategories, $perPage, $page);

        $context = $request->get('_context');
        $groups = ['groups' => $context];
        $data  = $this->serializeObject($documentCategories, $groups);

        $response = ['status' => Response::HTTP_OK, "success" => true, "count" => $count, "data" => $data];

        return new JsonResponse($response, Response::HTTP_OK);
    }
}