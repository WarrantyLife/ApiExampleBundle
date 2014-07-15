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
        return array('endpoint' => $endpoint);
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
        $args = array();

        $action = $request->request->get('action', null);

        switch ($action) {
            case 'get':
                $itemId = $request->request->get('itemId', null);
                if ($itemId) {
                    $url .= '/' . $itemId;
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
                break;

            case 'post':
                break;

            case 'put':
                $itemId = $request->request->get('itemId', null);
                if ($itemId) {
                    $url .= '/' . $itemId;
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
        }

        $apiResponse = $this->getResponse($apiRequest);

        return array('response' => $apiResponse);
    }
}
