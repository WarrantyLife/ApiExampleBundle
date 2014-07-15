<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use WarrantyLife\ApiExampleBundle\Controller\BaseController;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Controller for handling buyback examples
 */
class BuybackController extends BaseController
{
    /**
     * Display page that allows for interacting with the API
     *
     * @Route   ("/test/buybacks", name="apiexample_buybacks")
     * @Method  ("GET")
     * @Template()
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
     * @Route   ("/test/buybacks")
     * @Method  ("POST")
     * @Template()
     * @return array Data used in the twig template
     */
    public function postAction(Request $request)
    {
        $headers = array();
        $client  = $this->createClient($request);
        $action  = $request->request->get('action', null);
        $url     = $request->request->get('target', 'buybacks');

        switch ($action) {
            case 'get':
                $buybackId = $request->request->get('transactionId', null);

                if ($buybackId) {
                    $url .= '/' . $buybackId;
                } else {
                    $params = array();
                    foreach (array(
                                 'categoryId',
                                 'productId',
                                 'transactionId',
                                 'startAt',
                                 'limit') as $f) {
                        if ($v = $this->getRequest()->get($f, null)) {
                            if ($f == 'responses') {
                                $v = json_decode($v);
                                //{"54":3, "55":2, "56":3, "57":3}
                                foreach ($v as $key => $val) {
                                    $params[] = $f . '[' . $key . ']=' . $val;
                                }
                            } else {
                                $params[] = $f . '=' . $v;
                            }
                        }
                    }
                    if (count($params)) {
                        $url .= '?' . implode('&', $params);
                    }
                }
                $apiReq = $client->get($url, array('Content-Type' => 'application/json'));
                break;

            case 'post':
                $json   = $request->request->get('json', null);
                $apiReq = $client->post(array($url, $headers), array('Content-Type' => 'application/json'), $json);
                break;

            case 'put':
                $buybackId = $request->request->get('buybackId', null);
                if ($buybackId) {
                    $url .= '/' . $buybackId;
                    break;
                }
                $json   = $request->request->get('json', null);
                $apiReq = $client->put(array($url, $headers), array('Content-Type' => 'application/json'), $json);
                break;
            default:
                throw new \Exception('Error in API Test App - Unexpected or missing action input');
        }

        $apiResponse = $this->getResponse($apiReq);

        return array('response' => $apiResponse);
    }
}
