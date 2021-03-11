<?php


namespace App\Controller;


use App\Entity\Championship;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ChampionshipController extends DefaultController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function listAction(Request $request)
    {
        $championship = $this->entityManager->getRepository(Championship::class)->findAll();

        $context = $request->get('_context');

        $groups = ['groups' => $context];

        $response = $this->serializeObject($championship, $groups);

        return new JsonResponse($response, Response::HTTP_OK);
    }
}