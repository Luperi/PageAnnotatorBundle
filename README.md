PageAnnotatorBundle
===================

Installation
------------

Step 1: Download the bundle
---------------------------

Add this snippet of code in `composer.phar`:

```bash
"require": {
    // ...
    
    "luperi/page-annotator-bundle": "dev-master"
}
```

Step 2: Enable the bundle
-------------------------

Register the bundle in `app/AppKernel.php`:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...

        new Luperi\PageAnnotatorBundle\PageAnnotatorBundle(),
    );
}
```

Step 3: Composer update
-----------------------

Open a console window, enter into the project directory and run the following code:

```bash
    php composer.phar update
```
If you get memory errors, try increasing it with this code:

```bash
    php -dmemory_limit=1G composer.phar update
```

Step 4: Database connection parameters
--------------------------------------

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
                // ...
            annotation:
                connection: annotation
                mappings:
                    PageAnnotatorBundle:
                        type: annotation
```

Step 5: Database creation
-------------------------

Open a console window, enter into the project directory and run the following code:

```bash
    php app/console doctrine:database:create --connection=annotation
```

Step 6: Database table creation
-------------------------------

Open a console window, enter into the project directory and run the following code:

```bash
    php app/console doctrine:schema:update --force --em=annotation
```

Utilization
-----------

In your html page import the libraries by adding these lines:

``` javascript
    <script src="http://assets.annotateit.org/annotator/v1.2.7/annotator-full.min.js"></script>
    <script type="text/javascript" src="https://cdn.rawgit.com/Luperi/PageAnnotatorBundle/master/js/Luperi-annotatorjs.js.twig"></script>
```

And the style:

``` css
    <link rel="stylesheet" href="https://cdn.rawgit.com/Luperi/PageAnnotatorBundle/master/css/annotator.min.css">
```

Example with standard annotator
-------------------------------

``` html
<html>    
    <head>
        <script>
        $(function(){
            // Add the plugin that lets you save your annotations
            Annotator.Plugin.SavingAnnotation = (function() {
    
                function SavingAnnotation(element, options) {
                    this.element = element;
                    this.options = options;
                }
    
                SavingAnnotation.prototype.pluginInit = function() {
                    this.annotator
                            .subscribe("beforeAnnotationCreated", function (annotation) {
                                console.info("The annotation: %o is going to be created!", annotation);
                            })
                            .subscribe("annotationCreated", function (annotation) {
                                console.info("The annotation: %o has just been created!", annotation)
                                saveAnnotation(annotation, "<your_url>");
                            })
                            .subscribe("annotationUpdated", function (annotation) {
                                console.info("The annotation: %o has just been updated!", annotation);
                                saveAnnotation(annotation, "<your_url>");
                            })
                            .subscribe("annotationDeleted", function (annotation) {
                                console.info("The annotation: %o has just been deleted!", annotation);
                                deleteAnnotation(annotation, "<your_url>");
                            });
                };
    
                return SavingAnnotation;
            })();
    
            // Annotator init
            $('#container').annotator()
                            .annotator('addPlugin', 'SavingAnnotation');
    
            // Set annotator language, for example italian
            setAnnotatorLanguage("it");
        });
        </script>
    </head>
        <body>
            <div id='container'>
                Content that must be annotated
            </div>
        </body>
</html>
```

Example with fixed values
-------------------------

If you want to annotate with prefixed values instead of free comments, yuo have to modify your code in this way:

``` html
<html>    
    <head>
        <script>
        $(function(){
            // Add the plugin that lets you save your annotations
            Annotator.Plugin.SavingAnnotation = (function() {

                function SavingAnnotation(element, options) {
                    this.element = element;
                    this.options = options;
                }

                SavingAnnotation.prototype.pluginInit = function() {
                    this.annotator
                            .subscribe("beforeAnnotationCreated", function (annotation) {
                                console.info("The annotation: %o is going to be created!", annotation);
                                resetAnnotatorFixedValueSelector();
                            })
                            .subscribe("annotationCreated", function (annotation) {
                                console.info("The annotation: %o has just been created!", annotation)
                                saveAnnotationWithComment(annotation, "<your_url>", annotationCommentValue);
                            })
                            .subscribe("annotationUpdated", function (annotation) {
                                console.info("The annotation: %o has just been updated!", annotation);
                                saveAnnotationWithComment(annotation, "<your_url>", annotationCommentValue);
                            })
                            .subscribe("annotationDeleted", function (annotation) {
                                console.info("The annotation: %o has just been deleted!", annotation);
                                deleteAnnotation(annotation, "<your_url>");
                            });
                };

                return SavingAnnotation;
            })();

            // Annotator init
            $('#container').annotator()
                            .annotator('addPlugin', 'SavingAnnotation')
                            .annotator('addPlugin', 'Tags');

            // Set annotator language, for example italian
            setAnnotatorLanguage("it");
            
            var values = ["Tag1", "Tag2", "Tag3"];
            annotateWithFixedValues(values);
        });
        </script>
    </head>
        <body>
            <div id='container'>
                Content that must be annotated
            </div>
        </body>
</html>
```

Supported Languages
-------------------

- Italian -> "it"
- Spanish -> "es"
- French  -> "fr"
- German  -> "de"

Other available actions
-----------------------

You can easily delete all annotation in two ways.
If you want to delete all the annotations saved in the DB:

``` javascript
    deleteAllAnnotations();
```

Instead, if you want to delete only the annotations about a specific url, you can do this:

``` javascript
    deleteAllAnnotationsByUrl("<your_url>");
```
