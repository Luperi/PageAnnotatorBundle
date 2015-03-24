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
