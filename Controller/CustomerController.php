<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for handling customers
 */
class CustomerController extends BaseController
{
    /**
     * Display page that allows for interacting with the API
     *
     * @Route("/test/customers", name="apiexample_customer")
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
     * @Route("/test/customers")
     * @Method("POST")
     * @Template()
     *
     * @return array Data used in the twig template
     */
    public function postAction(Request $request)
    {
        $url  = 'customers';
        $client  = $this->createClient($request);
        $action  = $request->get('action');
        $params = [];
        $response = null;

        switch ($action) {
            case 'get':
                $customerId = $request->get('customerId');
                if ($customerId) {
                    $url .= '/' . $customerId;
                    break;
                }

                $refId = $request->get('refId');
                if ($refId) {
                    $params['refId'] = $refId;
                    break;
                }

                $orderRefId = $request->get('orderRefId');
                if ($orderRefId) {
                    $params['orderRefId'] = $orderRefId;
                    break;
                }
                break;

            case 'put':
                $customerId = $request->get('customerId');
                if ($customerId) {
                    $url .= '/' . $customerId;
                    break;
                }
                break;
        }

        switch ($action) {
            case 'get':
                $response = $client->get($url, ['query' => $params]);
                break;

            case 'post':
                $json   = $request->get('json');
                $response = $client->post($url, ['query' => $params, 'headers' => ['Content-Type' => 'application/json'], 'body' => $json]);
                break;

            case 'put':
                $json   = $request->request->get('json');
                $response = $client->put($url, ['query' => $params, 'headers' => ['Content-Type' => 'application/json'], 'body' => $json]);
                break;
        }

        $apiResponse = $response ?  $this->formatResponse($response) : '';

        return ['response' => $apiResponse];
    }
}
