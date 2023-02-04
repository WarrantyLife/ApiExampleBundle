========
Overview
========

This bundle is an example of the Warranty Life API can be used.


Installation
------------

First, checkout a copy of the code.  Just add the following  to the
``repositories`` or your  ``composer.json`` file of your Symfony Distribution::

    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/WarrantyLife/ApiExampleBundle.git"
        }
    ]

Next, install the bundle

    composer require warrantylife/api-example-bundle:dev-master

Then register the bundle with your kernel::

    // in AppKernel::registerBundles()
    $bundles = array(
        // ...
        new WarrantyLife\Bundle\ApiExampleBundle\WarrantyLifeApiExampleBundle(),
        // ...
    );

And add the bundle to your routing.yml file::

    // app/config/routing.yml
    WarrantyLifeApiExampleBundle:
        resource: "@WarrantyLifeApiExampleBundle/Controller/"
        type:     annotation
        prefix:   /warrantylife-api

You should now be able to go to /warrantylife-api to start testing the API.
