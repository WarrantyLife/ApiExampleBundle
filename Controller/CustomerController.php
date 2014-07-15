<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use WarrantyLife\ApiExampleBundle\Controller\BaseController;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

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
        return array('endpoint' => $endpoint);
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
        $args = array();

        $action = $request->request->get('action', null);

        switch ($action) {
            case 'get':
                $customerId = $request->request->get('customerId', null);
                if ($customerId) {
                    $url .= '/' . $customerId;
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
                $customerId = $request->request->get('customerId', null);
                if ($customerId) {
                    $url .= '/' . $customerId;
                    break;
                }
                break;
        }

        $client = $this->createClient($request);

        switch ($action) {
            case 'get':
                $apiReq = $client->get(array($url, $args));
                break;

            case 'post':
                $json   = $request->request->get('json', null);
                $apiReq = $client->post(array($url, $args), array('Content-Type' => 'application/json'), $json);
                break;

            case 'put':
                $json   = $request->request->get('json', null);
                $apiReq = $client->put(array($url, $args), array('Content-Type' => 'application/json'), $json);
                break;
        }

        $apiResponse = $this->getResponse($apiReq);

        return array('response' => $apiResponse);
    }
}
