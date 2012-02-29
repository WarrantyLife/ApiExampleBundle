<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use WarrantyLife\ApiExampleBundle\Controller\BaseController;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class CustomerController extends BaseController
{
    /**
     * @Route("/customers", name="apiexample_customer")
     * @Method("GET")
     * @Template()
     */
	public function indexAction()
	{
    	$endpoint = $this->container->getParameter('api_endpoint', 'https://sandbox.warrantylife.com/api/201201');

    	return array('endpoint'=>$endpoint);
    }

    /**
     * @Route("/customers")
     * @Method("POST")
     * @Template()
     */
    public function postAction()
    {
    	$url = 'customers';
    	$args = array();

    	$action = $this->getRequest()->request->get('action', null);

    	switch ($action) {
    		case 'get':
		    	$customerId = $this->getRequest()->request->get('customerId', null);
		    	if ($customerId) {
		    		$url .= '/'.$customerId;
	    			break;
		    	}

		    	$refId = $this->getRequest()->request->get('refId', null);
		    	if ($refId) {
		    		$url .= '{?refId}';
		    		$args['refId'] = $refId;
	    			break;
		    	}

		    	$orderRefId = $this->getRequest()->request->get('orderRefId', null);
		    	if ($orderRefId) {
		    		$url .= '{?orderRefId}';
		    		$args['orderRefId'] = $orderRefId;
	    			break;
		    	}
    			break;

    		case 'post':
    			break;

    		case 'put':
		    	$customerId = $this->getRequest()->request->get('customerId', null);
		    	if ($customerId) {
		    		$url .= '/'.$customerId;
	    			break;
		    	}
    			break;
    	}

    	$client = $this->createClient();

    	switch ($action) {
    		case 'get':
		    	$request = $client->get(array($url, $args));
    			break;

    		case 'post':
		    	$json = $this->getRequest()->request->get('json', null);
		    	$request = $client->post(array($url, $args), array('Content-Type'=>'application/json'), $json);
    			break;

    		case 'put':
		    	$json = $this->getRequest()->request->get('json', null);
		    	$request = $client->put(array($url, $args), array('Content-Type'=>'application/json'), $json);
    			break;
    	}

    	$response = $this->getResponse($request);

    	return array('response'=>$response);
    }
}
