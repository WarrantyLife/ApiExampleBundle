========
Overview
========

This bundle is an example of the Warranty Life API can be used.


Installation
------------

First, checkout a copy of the code.  Just add the following  to the ``deps`` 
file of your Symfony Standard Distribution::

    [ApiExampleBundle]
        git=git://github.com/WarrantyLife/ApiExampleBundle.git
        target=bundles/WarrantyLife/ApiExampleBundle

Then register the bundle with your kernel::

    // in AppKernel::registerBundles()
    $bundles = array(
        // ...
        new WarrantyLife\Bundle\ApiExampleBundle\WarrantyLifeApiExampleBundle(),
        // ...
    );

This bundle also requires the Guzzle library::

    [guzzle]
        git=git://github.com/guzzle/guzzle.git
        target=/guzzle


Make sure that you also register the namespaces with the autoloader::

    // app/autoload.php
    $loader->registerNamespaces(array(
        // ...
        'WarrantyLife'  => __DIR__.'/../vendor/bundles',
        'Guzzle'        => __DIR__.'/../vendor/guzzle/src',
        // ...
    ));

And add the bundle to your routing.yml file::

    // app/config/routing.yml
    WarrantyLifeApiExampleBundle:
        resource: "@WarrantyLifeApiExampleBundle/Controller/"
        type:     annotation
        prefix:   /warrantylife-api

Now use the ``vendors`` script to clone the newly added repositories 
into your project::

    php bin/vendors install

You should now be able to go to /warrantylife-api to start testing the API.
