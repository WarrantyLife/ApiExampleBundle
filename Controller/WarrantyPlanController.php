<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use WarrantyLife\ApiExampleBundle\Controller\BaseController;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Controller for handling service plans
 */
class WarrantyPlanController extends BaseController
{
    /**
     * Display page that allows for interacting with the API
     * 
     * @Route("/warranty-plans", name="apiexample_warranty_plans")
     * @Method("GET")
     * @Template()
     * 
     * @return array Data used in the twig template
     */
	public function indexAction()
	{
    	$endpoint = $this->container->getParameter('api_endpoint', 'https://sandbox.warrantylife.com/api/201201');

    	return array('endpoint'=>$endpoint);
    }

    /**
     * Perform API request
     * 
     * @Route("/warranty-plans")
     * @Method("POST")
     * @Template()
     * 
     * @return array Data used in the twig template
     */
    public function postAction()
    {
    	$url = 'warranty-plans';
    	$args = array();

    	$warrantyPlanId = $this->getRequest()->request->get('warrantyPlanId', null);
    	if ($warrantyPlanId) {
    		$url .= '/'.$warrantyPlanId;
    	}

    	$categoryId = $this->getRequest()->request->get('categoryId', null);
    	if ($categoryId) {
    		$url .= '{?categoryId}';
    		$args['categoryId'] = $categoryId;
    	}

    	$client = $this->createClient();

    	$request = $client->get(array($url, $args));
    	$response = $this->getResponse($request);

    	return array('response'=>$response);
    }
}
