<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use WarrantyLife\ApiExampleBundle\Controller\BaseController;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Controller for handling warranties
 */
class Warranty201204Controller extends WarrantyController
{
    /**
     * Display page that allows for interacting with the API
     *
     * @Route("/test/warranties201204", name="apiexample_warranty201204")
     * @Method("GET")
     * @Template()
     *
     * @return array Data used in the twig template
     */
    public function indexAction()
    {
        // This controller can only be used up to version 201204 of the API
        if ($this->getApiVersion() > 201204) {
            $this->setApiVersion(201204);
        }

        $endpoint = $this->getApiEndpoint();
        return ['endpoint' => $endpoint];
    }

    /**
     * Perform API request
     *
     * @Route("/test/warranties201204")
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
