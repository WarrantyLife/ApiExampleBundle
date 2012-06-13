<?php

namespace WarrantyLife\ApiExampleBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Display Developer API Documentation
 */
class DocumentationController extends Controller
{
    /**
     * @Route   ("/docs", name="api_docs")
     * @Route   ("/docs/201204", name="api_docs_201204")
     * @Template()
     */
    public function apiDoc201204Action()
    {
        $version = '201204';
        return array(
            'version' => $version,
            'prefix'  => '/api/' . $version,
            'sntTypesDescriptor' => '[ESN|IMEI|MEID]' //$this->getItemManager()->getSerialNumberTypesDescriptor()
        );
    }

    /**
     * @Route   ("/docs/201201", name="api_docs_201201")
     * @Template()
     */
    public function apiDoc201201Action()
    {
        $version = '201201';
        return array(
            'version' => $version,
            'prefix' => '/api/' . $version,
        );
    }
}

