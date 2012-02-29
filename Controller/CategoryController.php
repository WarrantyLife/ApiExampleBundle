<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use WarrantyLife\ApiExampleBundle\Controller\BaseController;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class CategoryController extends BaseController
{
    /**
     * @Route("/categories")
     * @Method("GET")
     * @Template()
     */
	public function indexAction()
	{
    	$endpoint = $this->container->getParameter('api_endpoint', 'https://sandbox.warrantylife.com/api/201201');

    	return array('endpoint'=>$endpoint);
    }

    /**
     * @Route("/categories")
     * @Method("POST")
     * @Template()
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
