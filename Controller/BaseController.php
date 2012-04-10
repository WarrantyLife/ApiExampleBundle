<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Guzzle\Http\Client;
use Guzzle\Common\Log\ZendLogAdapter;
use Guzzle\Http\Plugin\LogPlugin;
use Guzzle\Http\Plugin\BasicAuthPlugin;
use Guzzle\Http\Message\RequestInterface;

/**
 * Base controller to provide commonly used methods
 */
class BaseController extends Controller
{
    /**
     * @var resource File stream resource for capturing Http Client output
     */
	protected $_stream;

	/**
	 * Create an Http Client
	 * 
	 * @return Client A client instance
	 */
    protected function createClient()
    {
    	$stream = $this->createStream();
    	$adapter = new ZendLogAdapter(new \Zend\Log\Logger(new \Zend\Log\Writer\Stream($stream)));
    	$logPlugin = new LogPlugin($adapter, LogPlugin::LOG_VERBOSE);

    	$apiKey = $this->getRequest()->request->get('username', null);
    	$apiSecret = $this->getRequest()->request->get('password', null);

    	if ($this->container->hasParameter('api_endpoint')) {
    	    $endpoint = $this->container->getParameter('api_endpoint', 'https://sandbox.warrantylife.com/api/201201');
    	} else {
    	    $endpoint = 'https://sandbox.warrantylife.com/api/201201';
    	}
    	if (is_null($apiKey)) {
    		if ($this->container->hasParameter('api_key')) {
		    	$apiKey = $this->container->getParameter('api_key');
    		} else {
    			$apiKey = 'NOKEYFOUND';
    		}
    	}
    	if (is_null($apiSecret)) {
    		if ($this->container->hasParameter('api_key')) {
		    	$apiSecret = $this->container->getParameter('api_secret');
    		} else {
    			$apiSecret = 'NOSECRETFOUND';
    		}
    	}

    	$client = new Client($endpoint);

    	$client->getEventDispatcher()->addSubscriber($logPlugin);
    	$client->getEventDispatcher()->addSubscriber(new BasicAuthPlugin($apiKey, $apiSecret));
    	$client->setUserAgent('WarrantyLifeAPITesting/1.0', true);

    	return $client;
    }

    /**
     * Set the stream used to capture client output
     * 
     * @return resource A file stream resource
     */
    protected function createStream()
    {
    	return ($this->_stream = fopen('php://temp', 'r+'));
    }

    /**
     * Get the stream used to capture client output
     * 
     * @return resource A file stream resource
     */
    protected function getStream()
    {
    	return $this->_stream;
    }

    /**
     * Get the full response from the http client
     * 
     * @param RequestInterface The Http Client request
     * 
     * @return string Full response from the Http Client
     */
    protected function getResponse(RequestInterface $request)
    {
    	try {
	    	$response = $request->send();
    	} catch (\Guzzle\Http\Message\BadResponseException $e) {
    		//print_r('Bad Response');
    		//return $e->getResponse();
    	} catch (\Guzzle\Common\ExceptionCollection $e) {
    		foreach ($e as $exception) {
	    		print_r('Got Exception: '.get_class($exception));
    		}
	    	exit();
    	} catch (\Exception $e) {
    		print_r('Got Exception: '.get_class($e));
	    	exit();
    	}
//    	print_r($response);

    	//$body = $response->getBody(true);

    	$stream = $this->getStream();
    	rewind($stream);
    	$fullStream = stream_get_contents($stream);

    	return $fullStream;
    }
}