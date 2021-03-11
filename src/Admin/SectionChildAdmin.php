<?php


namespace App\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class SectionChildAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = "section_children";
    protected $baseRouteName = 'sonata_section_children';


//    protected function configureRoutes(RouteCollection $collection)
//    {
//    }
    protected function configureListFields(ListMapper $list)
    {

        dump("test");
        die();
    }

}