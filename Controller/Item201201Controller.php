<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use WarrantyLife\ApiExampleBundle\Controller\BaseController;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Controller for handling items
 */
class Item201201Controller extends ItemController
{
    /**
     * Display page that allows for interacting with the API
     *
     * @Route("/test/items201201", name="apiexample_item201201")
     * @Method("GET")
     * @Template()
     *
     * @return array Data used in the twig template
     */
    public function indexAction()
    {
        // This controller can only be used up to version 201204 of the API
        if ($this->getApiVersion() > 201201) {
            $this->setApiVersion(201201);
        }

        $endpoint = $this->getApiEndpoint();
        return ['endpoint' => $endpoint];
    }

    /**
     * Perform API request
     *
     * @Route("/test/items201201")
     * @Method("POST")
     * @Template()
     *
     * @return array Data used in the twig template
     */
    public function postAction(Request $request)
    {
        return parent::postAction($request);
    }
}
