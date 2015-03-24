Installation is very easy, it makes use of Composer.

Add SimpleHtmlDomBundle to your composer.json

"require": {
    "erivello/simple-html-dom-bundle": "dev-master"
}

Register the bundle in app/AppKernel.php:

<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...

        new Erivello\SimpleHtmlDomBundle\ErivelloSimpleHtmlDomBundle(),
    );
}
