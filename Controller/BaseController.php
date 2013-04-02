<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Guzzle\Http\Client;
use Guzzle\Log\Zf1LogAdapter;
use Guzzle\Log\Zf2LogAdapter;
use Guzzle\Plugin\Log\LogPlugin;
use Guzzle\Log\MessageFormatter;
use Guzzle\Plugin\CurlAuth\CurlAuthPlugin;
use Guzzle\Http\Message\RequestInterface;

/**
 * Base controller to provide commonly used methods
 */
class BaseController extends Controller
{
    /**
     * Used only when no parameters.yml var is set nor is the version specified as a parameter
     */
    const DEFAULT_API_VERSION = '201204';

    /**
     * @var resource File stream resource for capturing Http Client output
     */
    protected $_stream;

    /**
     * Base URL with protocol, domain and partial path excluding API version
     *
     * @see getApiBaseUrl
     * @var string
     */
    private $_apiBaseUrl;

    /**
     * Which API version to use; Will be appended to _apiEndpoint
     *
     * @var string
     */
    private $_apiVersion;

    /**
     * Get the API Endpoint base URL to use.  Will default to using parameters set in config files.

     */
    protected function getApiBaseUrl()
    {
        if (empty($this->_apiBaseUrl)) {
            $this->_apiBaseUrl = $this->container->getParameter('examplebundle_api_endpoint', 'https://sandbox.warrantylife.com/api/');
        }
        return $this->_apiBaseUrl;
    }

    /**
     * Manually set the API endpoint to use.
     *
     * @param $apiEndpoint
     */
    protected function setApiBaseUrl($apiEndpoint)
    {
        $this->_apiBaseUrl = $apiEndpoint;
    }

    /**
     * Get the API version in use
     */
    protected function getApiVersion()
    {
        if (empty($this->_apiVersion)) {
            if ($v = $this->getRequest()->get('v', false)) {
                $this->_apiVersion = $v;
            } else {
                $this->_apiVersion = $this->container->getParameter('examplebundle_api_version', self::DEFAULT_API_VERSION);
            }
        }
        return $this->_apiVersion;
    }

    /**
     * Set which API version to use
     *
     * @param string$apiVersion
     */
    protected function setApiVersion($apiVersion)
    {
        $this->_apiVersion = $apiVersion;
    }

    /**
     * Get the API endpoint to use including version number
     *
     * @param null $apiVersion
     *
     * @return string
     */
    protected function getApiEndpoint($apiVersion = null)
    {
        if (empty($apiVersion)) {
            $apiVersion = $this->getApiVersion();
        }
        return $this->getApiBaseUrl() . $apiVersion;
    }

    /**
     * Create an Http Client
     *
     * @return Client A client instance
     */
    protected function createClient()
    {
       //$stream    = $this->createStream();

        $adapter = new Zf1LogAdapter(
            new \Zend_Log(new \Zend_Log_Writer_Stream('php://output'))
        );
        //$adapter   = new Zf2LogAdapter(new \Zend\Log\Logger(new \Zend\Log\Writer\Stream('php://output')));
        $logPlugin = new LogPlugin($adapter, MessageFormatter::DEBUG_FORMAT);

        $apiKey    = $this->getRequest()->request->get('username', null);
        $apiSecret = $this->getRequest()->request->get('password', null);

        $endpoint = $this->getApiEndpoint();

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
        $client->getEventDispatcher()->addSubscriber(new CurlAuthPlugin($apiKey, $apiSecret));
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
                print_r('Got Exception: ' . get_class($exception));
            }
            exit();
        } catch (\Exception $e) {
            print_r('Got Exception: ' . get_class($e));
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