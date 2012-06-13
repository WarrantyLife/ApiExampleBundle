<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

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
     * @Route   ("/manufacturers", name="apiexample_manufacturer")
     * @Method  ("GET")
     * @Template()
     * @return array Data used in the twig template
     */
    public function indexAction()
    {
        $endpoint = $this->getApiEndpoint();
        return array('endpoint'=> $endpoint);
    }

    /**
     * Perform API request
     *
     * @Route   ("/manufacturers")
     * @Method  ("POST")
     * @Template()
     * @throws \Exception
     * @return array Data used in the twig template
     */
    public function postAction()
    {
        $url     = 'manufacturers';
        $headers = array();
        $client  = $this->createClient();
        $action  = $this->getRequest()->request->get('action', null);

        switch ($action) {
            case 'get':
                $manufacturerId = $this->getRequest()->request->get('manufacturerId', null);
                if ($manufacturerId) {
                    $url .= '/' . $manufacturerId;
                } else {
                    $params = array();
                    foreach (array(
                                 'name',
                                 'startAt',
                                 'limit') as $f) {
                        if ($v = $this->getRequest()->get($f, null)) {
                            $params[] = $f . '=' . $v;
                        }
                    }
                    if (count($params)) {
                        $url .= '?' . implode('&', $params);
                    }
                }
                $request = $client->get($url, $headers);
                break;

            case 'post':
                $json    = $this->getRequest()->request->get('json', null);
                $request = $client->post(array($url, $headers), array('Content-Type'=> 'application/json'), $json);
                break;

            default:
                throw new \Exception('Error in API Test App - Unexpected or missing action input');
        }

        $response = $this->getResponse($request);

        return array('response'=> $response);
    }



}
