<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for handling categories
 */
class CategoryController extends BaseController
{
    /**
     * Display page that allows for interacting with the API
     *
     * @Route("/test/categories", name="apiexample_category")
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
     * @Route("/test/categories")
     * @Method("POST")
     * @Template()
     *
     * @return array Data used in the twig template
     */
    public function postAction(Request $request)
    {
        $url = 'categories';

        $categoryId = $request->get('categoryId');
        if ($categoryId) {
            $url .= '/' . $categoryId;
        }

        $client      = $this->createClient($request);
        $response    = $client->get($url);
        $apiResponse = $this->formatResponse($response);

        return ['response' => $apiResponse];
    }
}
