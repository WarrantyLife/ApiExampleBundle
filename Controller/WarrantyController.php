<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for handling warranties
 */
class WarrantyController extends BaseController
{
    /**
     * Display page that allows for interacting with the API
     *
     * @Route("/test/warranties", name="apiexample_warranty")
     * @Method("GET")
     * @Template()
     *
     * @return array Data used in the twig template
     */
    public function indexAction()
    {
        // This controller can only be used after version 201308 of the API
        if ($this->getApiVersion() < 201308) {
            $this->setApiVersion(201308);
        }

        $endpoint = $this->getApiEndpoint();
        return ['endpoint' => $endpoint];
    }

    /**
     * Perform API request
     *
     * @Route("/test/warranties")
     * @Method("POST")
     * @Template()
     *
     * @return array Data used in the twig template
     */
    public function postAction(Request $request)
    {
        $url  = 'warranties';
        $client  = $this->createClient($request);
        $action  = $request->get('action');
        $params = [];
        $response = null;

        switch ($action) {
            case 'get':
                $warrantyId = $request->get('warrantyId');
                if ($warrantyId) {
                    $url .= '/' . $warrantyId;
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

                $itemId = $request->get('itemId');
                if ($itemId) {
                    $url            = "items/$itemId/warranties";
                    break;
                }
                break;

            case 'post':
                $itemId = $request->get('itemId');
                if ($itemId) {
                    $url            = "items/$itemId/warranties";
                    break;
                }
                break;

            case 'put':
            case 'delete':
                $warrantyId = $request->get('warrantyId');
                if ($warrantyId) {
                    $url .= '/' . $warrantyId;
                    break;
                }
                break;
        }

        switch ($action) {
            case 'get':
                $response = $client->get($url, ['query' => $params]);
                break;

            case 'post':
                $json       = $request->request->get('json');
                $response = $client->post($url, ['query' => $params, 'headers' => ['Content-Type' => 'application/json'], 'body' => $json]);
                break;

            case 'put':
                $json       = $request->request->get('json');
                $response = $client->put($url, ['query' => $params, 'headers' => ['Content-Type' => 'application/json'], 'body' => $json]);
                break;

            case 'delete':
                $apiRequest = $client->delete($url, ['query' => $params, 'headers' => ['Content-Type' => 'application/json']]);
                break;
        }

        $apiResponse = $response ?  $this->formatResponse($response) : '';

        return ['response' => $apiResponse];
    }
}
