<?php


namespace App\Controller;


use App\Entity\Driver;
use App\Entity\Event;
use App\Entity\License;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DriverController extends DefaultController
{

    private function getPastLicenses($driver , $decrementYear = -1)
    {
        $licenses = $this->entityManager->getRepository(License::class)->findLicenseByDriverIssuedDate($driver, new \DateTime("{$decrementYear} year"));
        if (count($licenses) > 0 || $decrementYear == -10) // go back 10 years check if the license not found to prevent going in infinite recursion
            return $licenses;
        --$decrementYear;
        return $this->getPastLicenses($driver, $decrementYear);
    }
    public function showAction(Request $request, $id)
    {
        $driver = $this->entityManager->getRepository(Driver::class)->find($id);

        if (!$driver){
            $status = Response::HTTP_NOT_FOUND;
            $response = ["status" => $status, "success" => false, "errors" => "Driver not found"];
            return new JsonResponse($response, $status);
        }
        $licenses = $this->entityManager->getRepository(License::class)->findLicenseByDriverIssuedDate($driver, new \DateTime('NOW'));
        if (count($licenses) === 0){ // if the driver doesn't have active license checks for the past licenses (previous year)
            $licenses = $this->getPastLicenses($driver);
        }
        $driver->currentLicenses = $licenses;
        $context = $request->get('_context');

        $groups = ['groups' => $context];

        $data = $this->serializeObject($driver, $groups);

        $response = ['status' => Response::HTTP_OK, "success" => true, "data" => $data];

        return new JsonResponse($response, Response::HTTP_OK);

    }
}