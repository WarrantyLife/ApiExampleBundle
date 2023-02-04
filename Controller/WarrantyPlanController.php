<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

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
        return ['endpoint' => $endpoint];
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
        $args = [];

        $warrantyPlanId = $request->get('warrantyPlanId');
        if ($warrantyPlanId) {
            $url .= '/' . $warrantyPlanId;
        }

        $categoryId = $request->get('categoryId');
        if ($categoryId) {
            $args['categoryId'] = $categoryId;
        }

        $client      = $this->createClient($request);
        $response    = $client->get($url, ['query' => $args]);
        $apiResponse = $this->formatResponse($response);

        return ['response' => $apiResponse];
    }
}
