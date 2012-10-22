<?php

namespace WarrantyLife\Bundle\ApiExampleBundle\Controller;

use WarrantyLife\Bundle\ApiExampleBundle\Controller\BaseController;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Controller for handling categories
 */
class CategoryController extends BaseController
{
    /**
     * Display page that allows for interacting with the API
     *
     * @Route("/test/categories", name="apiexample_category")
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
     * @Route("/test/categories")
     * @Method("POST")
     * @Template()
     * 
     * @return array Data used in the twig template
     */
    public function postAction()
    {
    	$url = 'categories';

    	$categoryId = $this->getRequest()->request->get('categoryId', null);
    	if ($categoryId) {
    		$url .= '/'.$categoryId;
    	}

    	$client = $this->createClient();

    	$request = $client->get($url);
    	$response = $this->getResponse($request);

    	return array('response'=>$response);
    }
}
