Installazione
=============

Step 1: download the bundle
----------------------------

Add this snippet of code in `composer.phar`:

```bash
"require": {
    // ...
    
    "luperi/page_annotator_bundle": "dev-master"
}
```

Step 2: enable the bundle
----------------------------

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
