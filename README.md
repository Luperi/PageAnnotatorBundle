Installation is very easy, it makes use of Composer.

Add PageAnnotatorBundle to your composer.json

"require": {
    // ...
    "luperi/page_annotator_bundle": "dev-master"
}

Register the bundle in app/AppKernel.php:

<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new luperi\PageAnnotatorBundle\PageAnnotatorBundle(),
    );
}
