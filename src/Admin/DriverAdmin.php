<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Driver;
use App\Form\LicenseNumberType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Form\Type\VichImageType;

final class DriverAdmin extends AbstractAdmin
{

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('create_license', $this->getRouterIdParameter() . '/license/create');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('firstName')
            ->add('lastName')
//            ->add('licenseNumber')
            ->add('idType')
            ->add('idNumber');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('firstName')
            ->add('lastName')
//            ->add('licenseNumberNumber',null, [
//                'label' => 'License Number'
//            ])
//            ->add('licenseNumberDate', null, [
//                'label' => 'License Date'
//            ])
////            ->add('licenseNumber')
            ->add('idType')
            ->add('idNumber')
            ->add('bloodType')
////            ->add('status')
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                    'create_license' => ['template' => 'list_button.html.twig', 'route' => 'create_license', 'label' => 'Create License', 'icon' => 'fa fa-plus'],

                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add(
                'imageFile',
                VichImageType::class,
                [
                    'required' => false,
                    'label' => 'Image',

                ])
            ->add('firstName')
            ->add('lastName')
//            ->add('licenseNumber', CollectionType::class, [
//                'allow_add' => true,
//                'allow_delete' => true,
//                'entry_type' => LicenseNumberType::class,
//                'label' => 'License Number'
//            ])
            ->add('idType')
            ->add('idNumber')
            ->add('bloodType', ChoiceType::class, [
                'choices' => Driver::BLOOD_TYPES
            ])
            ->add('dateOfBirth', DatePickerType::class, [
                'format' => 'yyyy-MM-dd'
            ]);
    }

//    public function postPersist($object)
//    {
//        if ($object->getImage()) {
//            $imageFile = $object->getImageFile();
//            $this->resizeImage($imageFile->getPathname());
//        }
//    }
//
//    public function postUpdate($object)
//    {
//        if ($object->getImage()) {
//            $imageFile = $object->getImageFile();
//            $this->resizeImage($imageFile->getPathname());
//        }
//
//    }

//    public function resizeImage($file)
//    {
//        if (file_exists($file)) {
//            $originalImage = imagecreatefromjpeg($file);
//            $originalWidth = imagesx($originalImage);
//            $originalHeight = imagesy($originalImage);
//            $newWidth = 80;
//            $newHeight = 100;
//            if ($originalImage) {
//                $newImage = imagecreatetruecolor($newWidth, $newHeight);
//                imagecopyresampled($newImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
//                imagejpeg($newImage, $file, 100);
//            }
//        }
//    }
//    function resize_image($file, $w, $h, $crop=FALSE) {
//        list($width, $height) = getimagesize($file);
//        $r = $width / $height;
//        if ($crop) {
//            if ($width > $height) {
//                $width = ceil($width-($width*abs($r-$w/$h)));
//            } else {
//                $height = ceil($height-($height*abs($r-$w/$h)));
//            }
//            $newwidth = $w;
//            $newheight = $h;
//        } else {
//            if ($w/$h > $r) {
//                $newwidth = $h*$r;
//                $newheight = $h;
//            } else {
//                $newheight = $w/$r;
//                $newwidth = $w;
//            }
//        }
//        $src = imagecreatefromjpeg($file);
//        $dst = imagecreatetruecolor($newwidth, $newheight);
//        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
//
//        return $dst;
//    }

}
