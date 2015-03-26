PageAnnotatorBundle
===================

Installation
------------

Step 1: download the bundle
---------------------------

Add this snippet of code in `composer.phar`:

```bash
"require": {
    // ...
    
    "luperi/page_annotator_bundle": "dev-master"
}
```

Step 2: enable the bundle
-------------------------

Register the bundle in `app/AppKernel.php`:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...

        new luperi\PagenAnnotatorBundle\PageAnnotatorBundle(),
    );
}
```

Step 3: composer update
-----------------------

Open a console window, enter into the project directory and run the following code:

```bash
    php composer.phar update
```
If you get memory errors, try increasing it with this code:

```bash
    php -dmemory_limit=1G composer.phar update
```

Step 4: update DB connection parameters
---------------------------------------

Edit `app/config/config.yml` adding the following lines:

``` yml
# Doctrine Configuration
doctrine:
    dbal:
        default_connection: default
        connections:
            default:
                driver:   %database_driver%
                host:     %database_host%
                port:     %database_port%
                dbname:   %database_name%
                user:     %database_user%
                password: %database_password%
                charset:  UTF8
            annotation:
                driver:   %database_driver%
                host:     %database_host%
                port:     %database_port%
                dbname:   annotations_DB
                user:     %database_user%
                password: %database_password%
                charset:  UTF8

    orm:
        auto_generate_proxy_classes: %kernel.debug%
        default_entity_manager: default
        entity_managers:
            default:
                connection: default
                dql:
                    numeric_functions:
                        GEO_DISTANCE: Craue\GeoBundle\Doctrine\Query\Mysql\GeoDistance
                mappings:
                    SmartDbBundle:
                        type: annotation
            annotation:
                connection: annotation
                mappings:
                    PageAnnotatorBundle:
                        type: annotation
```

Step 5: database creation
-------------------------

Open a console window, enter into the project directory and run the following code:

```bash
    php app/console doctrine:database:create --connection=annotation
```

Step 6: database table creation
-------------------------------

Open a console window, enter into the project directory and run the following code:

```bash
    php app/console doctrine:schema:update --force --em=annotation
```
