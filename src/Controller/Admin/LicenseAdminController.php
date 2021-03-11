<?php


namespace App\Controller\Admin;


use App\Entity\License;
use DateInterval;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LicenseAdminController extends DefaultAdminController
{
    private $knpSnappyPdf;
    public function __construct(Pdf $knpSnappyPdf)
    {
        $this->knpSnappyPdf = $knpSnappyPdf;
    }

    public function renewAction($id)
    {
        $license = $this->admin->getSubject();
        $newLicense = clone $license;
        $license->setPublished(false);
        $newLicense->setIssuedDate(new \DateTime('now'));
        $newLicense->setExpiryDate($newLicense->getExpiryDate()->add(DateInterval::createFromDateString('1 year')));
        $this->admin->createLicenseNumber($newLicense, $newLicense->getExpiryDate()->format('Y'));
        try{
            $this->admin->create($newLicense);
            $this->addFlash(
                'sonata_flash_success',
                'License renewed'
            );
        }
        catch (\Exception $exception)
        {
            $this->addFlash(
                'sonata_flash_error',
                'Failed to renew the license'
            );
        }
        return $this->redirectToList();
    }

    public function printAction(Request $request, $id, Pdf $knpSnappyPdf)
    {
        $knpSnappyPdf->setOption("enable-local-file-access",true);
        $html = $this->renderView('print/license.html.twig', [
            'objects' => [$this->admin->getSubject()],
            'base_dir' => $this->get('kernel')->getRootDir() . '/../public' . $request->getBasePath()
        ]);

        return new PdfResponse(
            $knpSnappyPdf->getOutputFromHtml($html),
            'file.pdf'
        );
    }

//    public function printAction(Request  $request, $id)
//    {
//        return $this->render('print/license.html.twig', [
//            'objects' => [$this->admin->getSubject()],
//            'base_dir' => ''
//        ]);
//    }

    public function batchActionPrint(ProxyQueryInterface $selectedModelQuery, Request $request = null)
    {
        $licenses = $selectedModelQuery->execute();

        $this->knpSnappyPdf->setOption("enable-local-file-access",true);
        $html = $this->renderView('print/license.html.twig', [
            'objects' => $licenses,
            'base_dir' => $this->get('kernel')->getRootDir() . '/../public' . $request->getBasePath()
        ]);

        return new PdfResponse(
            $this->knpSnappyPdf->getOutputFromHtml($html),
            'file.pdf'
        );


        return $this->render('print/license.html.twig', [
            'objects' => $licenses,
            'base_dir' => ''
        ]);
    }

}