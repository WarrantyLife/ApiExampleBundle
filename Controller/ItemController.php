<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

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
        $endpoint = $this->getApiEndpoint();
    	return array('endpoint'=>$endpoint);
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
