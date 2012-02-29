<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use WarrantyLife\ApiExampleBundle\Controller\BaseController;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ItemController extends BaseController
{
    /**
     * @Route("/items", name="apiexample_item")
     * @Method("GET")
     * @Template()
     */
	public function indexAction()
	{
    	$endpoint = $this->container->getParameter('api_endpoint', 'https://sandbox.warrantylife.com/api/201201');

    	return array('endpoint'=>$endpoint);
    }

    /**
     * @Route("/items")
     * @Method("POST")
     * @Template()
     */
    public function postAction()
    {
    	$url = 'items';
    	$args = array();

    	$action = $this->getRequest()->request->get('action', null);

    	switch ($action) {
    		case 'get':
		    	$itemId = $this->getRequest()->request->get('itemId', null);
		    	if ($itemId) {
		    		$url .= '/'.$itemId;
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
		    	$itemId = $this->getRequest()->request->get('itemId', null);
		    	if ($itemId) {
		    		$url .= '/'.$itemId;
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
