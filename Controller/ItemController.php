<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for handling items
 */
class ItemController extends BaseController
{
    /**
     * Display page that allows for interacting with the API
     *
     * @Route("/test/items", name="apiexample_item")
     * @Method("GET")
     * @Template()
     *
     * @return array Data used in the twig template
     */
    public function indexAction()
    {
        // This controller can only be used after version 201204 of the API
        if ($this->getApiVersion() < 201204) {
            $this->setApiVersion(201204);
        }
        $endpoint = $this->getApiEndpoint();
        return ['endpoint' => $endpoint];
    }

    /**
     * Perform API request
     *
     * @Route("/test/items")
     * @Method("POST")
     * @Template()
     *
     * @return array Data used in the twig template
     */
    public function postAction(Request $request)
    {
        $url  = 'items';
        $client  = $this->createClient($request);
        $action  = $request->get('action');
        $params = [];
        $response = null;

        switch ($action) {
            case 'get':
                $itemId = $request->request->get('itemId');
                if ($itemId) {
                    $url .= '/' . $itemId;
                    break;
                }

                $refId = $request->request->get('refId');
                if ($refId) {
                    $params['refId'] = $refId;
                    break;
                }

                $orderRefId = $request->request->get('orderRefId');
                if ($orderRefId) {
                    $params['orderRefId'] = $orderRefId;
                    break;
                }
                break;

            case 'put':
                $itemId = $request->request->get('itemId');
                if ($itemId) {
                    $url .= '/' . $itemId;
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
        }

        $apiResponse = $response ?  $this->formatResponse($response) : '';

        return ['response' => $apiResponse];
    }
}
