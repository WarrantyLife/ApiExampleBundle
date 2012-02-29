<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use WarrantyLife\ApiExampleBundle\Controller\BaseController;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class BulkWarrantyController extends BaseController
{
    /**
     * @Route("/bulk/warranties", name="apiexample_bulk_warranty")
     * @Method("GET")
     * @Template()
     */
	public function indexAction()
	{
    	$endpoint = $this->container->getParameter('api_endpoint', 'https://sandbox.warrantylife.com/api/201201');

    	return array('endpoint'=>$endpoint);
    }

    /**
     * @Route("/bulk/warranties")
     * @Method("POST")
     * @Template()
     */
    public function postAction()
    {
    	$url = 'bulk/warranties';
    	$args = array();

    	$action = $this->getRequest()->request->get('action', null);

    	$client = $this->createClient();

    	$json = $this->getRequest()->request->get('json', null);
    	$request = $client->post(array($url, $args), array('Content-Type'=>'application/json'), $json);

    	$response = $this->getResponse($request);

    	return array('response'=>$response);
    }
}
