<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Handle default pages
 */
class DefaultController extends Controller
{
    /**
     * Show list of pages the user can go to
     * 
     * @Route("/", name="apiexample_root")
     * @Template()
     * 
     * @return array Data used in the twig template
     */
    public function indexAction()
    {
        return array();
    }
}
