<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use WarrantyLife\ApiExampleBundle\Controller\BaseController;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

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
        return array('endpoint' => $endpoint);
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
        $headers = array();
        $client  = $this->createClient($request);
        $action  = $request->request->get('action', null);

        switch ($action) {
            case 'get':
                $manufacturerId = $request->request->get('manufacturerId', null);
                if ($manufacturerId) {
                    $url .= '/' . $manufacturerId;
                } else {
                    $params = array();
                    foreach (array(
                                 'name',
                                 'hasBuyback',
                                 'startAt',
                                 'limit') as $f) {
                        if ($v = $request->get($f, null)) {
                            $params[] = $f . '=' . $v;
                        }
                    }
                    if (count($params)) {
                        $url .= '?' . implode('&', $params);
                    }
                }
                $apiReq = $client->get($url, $headers);
                break;

            case 'post':
                $json   = $request->request->get('json', null);
                $apiReq = $client->post(array($url, $headers), array('Content-Type' => 'application/json'), $json);
                break;

            default:
                throw new \Exception('Error in API Test App - Unexpected or missing action input');
        }

        $apiResponse = $this->getResponse($apiReq);

        return array('response' => $apiResponse);
    }


}
