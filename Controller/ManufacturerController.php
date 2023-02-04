<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for handling manufacturer examples
 */
class ManufacturerController extends BaseController
{
    /**
     * Display page that allows for interacting with the API
     *
     * @Route   ("/test/manufacturers", name="apiexample_manufacturer")
     * @Method  ("GET")
     * @Template()
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
     * @Route   ("/test/manufacturers")
     * @Method  ("POST")
     * @Template()
     * @throws \Exception
     * @return array Data used in the twig template
     */
    public function postAction(Request $request)
    {
        $url     = 'manufacturers';
        $client  = $this->createClient($request);
        $action  = $request->get('action');
        $params = [];
        $response = null;

        switch ($action) {
            case 'get':
                $manufacturerId = $request->get('manufacturerId');
                if ($manufacturerId) {
                    $url .= '/' . $manufacturerId;
                } else {
                    foreach (['name', 'startAt', 'limit'] as $f) {
                        if ($v = $request->get($f)) {
                            $params[$f] = $v;
                        }
                    }
                }
                $response = $client->get($url, ['query' => $params]);
                break;

            case 'post':
                $json   = $request->get('json');
                $response = $client->post($url, ['query' => $params, 'headers' => ['Content-Type' => 'application/json'], 'body' => $json]);
                break;

            default:
                throw new \Exception('Error in API Test App - Unexpected or missing action input');
        }

        $apiResponse = $response ?  $this->formatResponse($response) : '';

        return ['response' => $apiResponse];
    }


}
