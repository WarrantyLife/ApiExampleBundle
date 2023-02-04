<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for handling product examples
 */
class ProductController extends BaseController
{
    /**
     * Display page that allows for interacting with the API
     *
     * @Route   ("/test/products", name="apiexample_product")
     * @Method  ("GET")
     * @Template()
     * @return array Data used in the twig template
     */
    public function indexAction()
    {
        $endpoint = $this->getApiEndpoint();
        return ['endpoint' => $endpoint];
    }

    /**
     * Perform API request
     *
     * @Route   ("/test/products")
     * @Method  ("POST")
     * @Template()
     * @return array Data used in the twig template
     * @throws \Exception
     */
    public function postAction(Request $request)
    {
        $url     = 'products';
        $client  = $this->createClient($request);
        $action  = $request->get('action');
        $params = [];
        $response = null;


        switch ($action) {
            case 'get':
                $productId = $request->get('productId');
                if ($productId) {
                    $url .= '/' . $productId;
                }

                foreach (['id', 'mpn', 'model', 'categoryId', 'sku', 'upc', 'q', 'manufacturerId', 'manufacturerName', 'includePlans', 'includePlansAtPrice', 'startAt', 'limit'] as $f) {
                    if ($v = $request->get($f)) {
                        $params[$f] = $v;
                    }
                }

                $response = $client->get($url, ['query' => $params]);
                break;

            case 'post':
                $json   = $request->get('json');
                $response = $client->post($url, ['query' => $params, 'headers' => ['Content-Type' => 'application/json'], 'body' => $json]);
                break;

            case 'put':
                $productId = $request->get('productId');
                if ($productId) {
                    $url .= '/' . $productId;
                    break;
                }
                $json   = $request->get('json');
                $response = $client->put($url, ['query' => $params, 'headers' => ['Content-Type' => 'application/json'], 'body' => $json]);
                break;
            default:
                throw new \Exception('Error in API Test App - Unexpected or missing action input');
        }

        $apiResponse = $response ?  $this->formatResponse($response) : '';

        return ['response' => $apiResponse];
    }
}
