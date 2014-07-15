<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use WarrantyLife\ApiExampleBundle\Controller\BaseController;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Controller for handling service plans
 */
class WarrantyPlanController extends BaseController
{
    /**
     * Display page that allows for interacting with the API
     *
     * @Route("/test/warranty-plans", name="apiexample_warranty_plans")
     * @Method("GET")
     * @Template()
     *
     * @return array Data used in the twig template
     */
    public function indexAction()
    {
        $endpoint = $this->getApiEndpoint();
        return array('endpoint' => $endpoint);
    }

    /**
     * Perform API request
     *
     * @Route("/test/warranty-plans")
     * @Method("POST")
     * @Template()
     *
     * @return array Data used in the twig template
     */
    public function postAction(Request $request)
    {
        $url  = 'warranty-plans';
        $args = array();

        $warrantyPlanId = $request->request->get('warrantyPlanId', null);
        if ($warrantyPlanId) {
            $url .= '/' . $warrantyPlanId;
        }

        $categoryId = $request->request->get('categoryId', null);
        if ($categoryId) {
            $url .= '{?categoryId}';
            $args['categoryId'] = $categoryId;
        }

        $client = $this->createClient($request);

        $apiReq      = $client->get(array($url, $args));
        $apiResponse = $this->getResponse($apiReq);

        return array('response' => $apiResponse);
    }
}
