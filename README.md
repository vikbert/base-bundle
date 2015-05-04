base-bundle
==============


[![Build Status](https://secure.travis-ci.org/asgoodasnu/base-bundle.png?branch=master)](http://travis-ci.org/asgoodasnu/base-bundle) [![Total Downloads](https://poser.pugx.org/asgoodasnu/base-bundle/downloads.png)](https://packagist.org/packages/asgoodasnu/base-bundle) [![Latest Stable Version](https://poser.pugx.org/asgoodasnu/base-bundle/v/stable.png)](https://packagist.org/packages/asgoodasnu/base-bundle) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/8cfd7fe5-edb1-4db0-941c-ff24340364d1/mini.png)](https://insight.sensiolabs.com/projects/8cfd7fe5-edb1-4db0-941c-ff24340364d1)

Installation
------------

Install via composer:

    composer.phar require asgoodasnu/base-bundle
    
Register your bundle in the `AppKernel.php`

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Wk\BaseBundle\WkBaseBundle(),
            // ...
        );
    
        return $bundles;
    }

Configuration
-------------

Add persistance service for MongoDB:

    services:
        wk_base.abstract:
            class: Wk\BaseBundle\Services\PersistenceService
            abstract: true
            arguments: ['@doctrine_mongodb.odm.document_manager']
            calls:
                - [setLogger, ["@logger"]]
