<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use WarrantyLife\ApiExampleBundle\Controller\BaseController;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Controller for handling warranties
 */
class Warranty201204Controller extends BaseController
{
    /**
     * Display page that allows for interacting with the API
     * 
     * @Route("/test/warranties201204", name="apiexample_warranty201204")
     * @Method("GET")
     * @Template()
     * 
     * @return array Data used in the twig template
     */
	public function indexAction()
	{
        // This controller can only be used up to version 201204 of the API
        if ($this->getApiVersion() > 201204) {
            $this->setApiVersion(201204);
        }

        $endpoint = $this->getApiEndpoint();
    	return array('endpoint'=>$endpoint);
    }

    /**
     * Perform API request
     * 
     * @Route("/test/warranties201204")
     * @Method("POST")
     * @Template()
     * 
     * @return array Data used in the twig template
     */
    public function postAction()
    {
    	$url = 'warranties';
    	$args = array();

    	$action = $this->getRequest()->request->get('action', null);

    	switch ($action) {
    		case 'get':
		    	$warrantyId = $this->getRequest()->request->get('warrantyId', null);
		    	if ($warrantyId) {
		    		$url .= '/'.$warrantyId;
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

		    	$itemId = $this->getRequest()->request->get('itemId', null);
		    	if ($itemId) {
		    		$url = 'items/{itemId}/warranties';
		    		$args['itemId'] = $itemId;
	    			break;
		    	}
    			break;

    		case 'post':
		    	$itemId = $this->getRequest()->request->get('itemId', null);
		    	if ($itemId) {
		    		$url = 'items/{itemId}/warranties';
		    		$args['itemId'] = $itemId;
	    			break;
		    	}
    			break;

    		case 'put':
		    	$warrantyId = $this->getRequest()->request->get('warrantyId', null);
		    	if ($warrantyId) {
		    		$url .= '/'.$warrantyId;
	    			break;
		    	}
    			break;

    		case 'delete':
		    	$warrantyId = $this->getRequest()->request->get('warrantyId', null);
		    	if ($warrantyId) {
		    		$url .= '/'.$warrantyId;
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

    		case 'delete':
		    	$request = $client->delete(array($url, $args));
    			break;
    	}

    	$response = $this->getResponse($request);

    	return array('response'=>$response);
    }
}