========
Overview
========

This bundle is an example of the Warranty Life API can be used.


Installation
------------

First, checkout a copy of the code.  Just add the following  to the ``deps`` 
file of your Symfony Standard Distribution::

    [ApiExampleBundle]
        git=https://bitbucket.org/WarrantyLife/apiexamplebundle.git
        target=bundles/WarrantyLife/ApiExampleBundle

Then register the bundle with your kernel::

    // in AppKernel::registerBundles()
    $bundles = array(
        // ...
        new WarrantyLife\ApiExampleBundle\WarrantyLifeApiExampleBundle(),
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

Now use the ``vendors`` script to clone the newly added repositories 
into your project::

    php bin/vendors install

