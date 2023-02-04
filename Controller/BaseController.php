<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use Exception;
use Guzzle\Common\Exception\ExceptionCollection;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Message\RequestInterface;
use Guzzle\Log\MessageFormatter;
use Guzzle\Log\PsrLogAdapter;
use Guzzle\Plugin\CurlAuth\CurlAuthPlugin;
use Guzzle\Plugin\Log\LogPlugin;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Base controller to provide commonly used methods
 */
class BaseController extends Controller
{
    /**
     * Used only when no parameters.yml var is set nor is the version specified as a parameter
     */
    public const DEFAULT_API_VERSION = '201308';

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
     * @param string $apiVersion
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
    protected function getApiEndpoint($apiVersion = null): string
    {
        if (empty($apiVersion)) {
            $apiVersion = $this->getApiVersion();
        }
        return $this->getApiBaseUrl() . $apiVersion . '/';
    }

    /**
     * Create an Http Client
     *
     * @return Client A client instance
     */
    protected function createClient(Request $request): Client
    {
        $apiKey    = $request->get('username');
        $apiSecret = $request->get('password');
        $endpoint  = $this->getApiEndpoint();

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

        return new Client([
            'base_uri' => $endpoint,
            'auth' => [$apiKey, $apiSecret, 'Basic'],
            'headers' => ['User-Agent' => 'WarrantyLifeAPITesting/1.0']
        ]);
    }

    /**
     * Get the full response from the http client
     *
     * @param ResponseInterface $request The Http Client request
     *
     * @return string Full response from the Http Client
     */
    protected function getResponse(ResponseInterface $request)
    {
        try {
            $response = $request->send();
        } catch (ClientErrorResponseException|RequestException $e) {
        } catch (ServerErrorResponseException $e) {
            // Show the returned stream for these exceptions
        } catch (BadResponseException $e) {
            //print_r('Bad Response');
            //return $e->getResponse();
        } catch (ExceptionCollection $e) {
            foreach ($e as $exception) {
                print_r('Got Exception: ' . get_class($exception));
            }
            exit();
        } catch (Exception $e) {
            print_r('Got Exception: ' . get_class($e));
        }
//    	print_r($response);

        //$body = $response->getBody(true);

        $stream = $this->getStream();
        if ($stream) {
            try {
                rewind($stream);
                $fullStream = stream_get_contents($stream);
            } catch (\Exception $e) {
                $fullStream = '';
            }
        } else {
            $fullStream = '';
        }

        return $fullStream;
    }

    public function formatResponse(ResponseInterface $response)
    {
        $responseText = 'HTTP/' . $response->getProtocolVersion() . ' ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase() . "\n";

        foreach ($response->getHeaders() as $name => $values) {
            $responseText .= $name . ': ' . implode(', ', $values) . "\n";
        }

        $responseText .= "\n\n";

        $body = $response->getBody();

        $responseText .= $body;

        return $responseText;

    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->get('request_stack')->getCurrentRequest();
    }
}
