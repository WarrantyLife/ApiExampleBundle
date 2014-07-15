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
        return array('endpoint' => $endpoint);
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
        $args = array();

        $action = $request->request->get('action', null);

        switch ($action) {
            case 'get':
                $warrantyId = $request->request->get('warrantyId', null);
                if ($warrantyId) {
                    $url .= '/' . $warrantyId;
                    break;
                }

                $refId = $request->request->get('refId', null);
                if ($refId) {
                    $url .= '{?refId}';
                    $args['refId'] = $refId;
                    break;
                }

                $orderRefId = $request->request->get('orderRefId', null);
                if ($orderRefId) {
                    $url .= '{?orderRefId}';
                    $args['orderRefId'] = $orderRefId;
                    break;
                }

                $itemId = $request->request->get('itemId', null);
                if ($itemId) {
                    $url            = 'items/{itemId}/warranties';
                    $args['itemId'] = $itemId;
                    break;
                }
                break;

            case 'post':
                $itemId = $request->request->get('itemId', null);
                if ($itemId) {
                    $url            = 'items/{itemId}/warranties';
                    $args['itemId'] = $itemId;
                    break;
                }
                break;

            case 'put':
                $warrantyId = $request->request->get('warrantyId', null);
                if ($warrantyId) {
                    $url .= '/' . $warrantyId;
                    break;
                }
                break;

            case 'delete':
                $warrantyId = $request->request->get('warrantyId', null);
                if ($warrantyId) {
                    $url .= '/' . $warrantyId;
                    break;
                }
                break;
        }

        $client = $this->createClient($request);

        switch ($action) {
            case 'get':
                $apiRequest = $client->get(array($url, $args));
                break;

            case 'post':
                $json       = $request->request->get('json', null);
                $apiRequest = $client->post(array($url, $args), array('Content-Type' => 'application/json'), $json);
                break;

            case 'put':
                $json       = $request->request->get('json', null);
                $apiRequest = $client->put(array($url, $args), array('Content-Type' => 'application/json'), $json);
                break;

            case 'delete':
                $apiRequest = $client->delete(array($url, $args));
                break;
        }

        $apiResponse = $this->getResponse($apiRequest);

        return array('response' => $apiResponse);
    }
}
