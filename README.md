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
    <script type="text/javascript" src="http://assets.annotateit.org/annotator/v1.2.10/annotator-full.min.js"></script>
    <script type="text/javascript" src="{{ path("page_annotator_library_js") }}"></script>
```

And the style:

``` css
    <link rel="stylesheet" href="http://assets.annotateit.org/annotator/v1.2.10/annotator.min.css">
```

Example with standard annotator
-------------------------------

N.B.: the two following examples use twig to resolve the routes

``` html
<html>    
    <head>
        <script type="text/javascript" src="http://assets.annotateit.org/annotator/v1.2.10/annotator-full.min.js"></script>
        <script type="text/javascript" src="{{ path("page_annotator_library_js") }}"></script>
        <link rel="stylesheet" href="http://assets.annotateit.org/annotator/v1.2.10/annotator.min.css">
        
        <script>
        $(function(){
            // Add store plugin
            $('#container').annotator()
                            .annotator('addPlugin', 'Store',
                                    {
                                        prefix: '',

                                        annotationData: {
                                            'uri': '<your_url>'
                                        },

                                        loadFromSearch:
                                        {
                                            'limit': 0,
                                            'uri' : '<your_url>'
                                        },

                                        urls: {
                                            // These are the default URLs.
                                            create:  '{{ path("page_annotator_save") }}',
                                            update:  '{{ path("page_annotator_save") }}',
                                            destroy: '{{ path("page_annotator_delete") }}',
                                            search:  '{{ path("page_annotator_search", { 'uri' : '<your_url>' }) }}'
                                        }
                                    });

            // Set Annotator Language, for example Italian
            setAnnotatorLanguage("it");
        });
        </script>
    </head>
        <body>
            <div id='container'>
                Lorem Ipsum è un testo segnaposto utilizzato nel settore della tipografia e della stampa. Lorem Ipsum è considerato il testo segnaposto standard sin dal sedicesimo secolo, quando un anonimo tipografo prese una cassetta di caratteri e li assemblò per preparare un testo campione. È sopravvissuto non solo a più di cinque secoli, ma anche al passaggio alla videoimpaginazione, pervenendoci sostanzialmente inalterato. Fu reso popolare, negli anni ’60, con la diffusione dei fogli di caratteri trasferibili “Letraset”, che contenevano passaggi del Lorem Ipsum, e più recentemente da software di impaginazione come Aldus PageMaker, che includeva versioni del Lorem Ipsum.
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
        <script type="text/javascript" src="http://assets.annotateit.org/annotator/v1.2.10/annotator-full.min.js"></script>
        <script type="text/javascript" src="{{ path("page_annotator_library_js") }}"></script>
        <link rel="stylesheet" href="http://assets.annotateit.org/annotator/v1.2.10/annotator.min.css">
        
        <script>
        $(function(){
            // Init plugins
            Annotator.Plugin.SavingAnnotation = (function() {

                function SavingAnnotation(element, options) {
                    this.element = element;
                    this.options = options;
                }

                SavingAnnotation.prototype.pluginInit = function() {
                    this.annotator
                            .subscribe("beforeAnnotationCreated", function (annotation) {
                                console.info("The annotation: %o is going to be created!", annotation);
                                setAnnotatorFixedValueSelector("null");
                            })
                            .subscribe("annotationCreated", function (annotation) {
                                console.info("The annotation: %o has just been created!", annotation)
                                annotation.text = annotationCommentValue;
                            })
                            .subscribe("annotationEditorShown", function (editor, annotation) {
                                console.info("The annotation: %o is going to be updated!", annotation);
                                setAnnotatorFixedValueSelector(annotation.text);
                            })
                            .subscribe("annotationUpdated", function (annotation) {
                                console.info("The annotation: %o has just been updated!", annotation);
                                annotation.text = annotationCommentValue;
                            })
                };
                return SavingAnnotation;
            })();

            // Add all necessary plugins
            $('#container').annotator()
                            .annotator('addPlugin', 'SavingAnnotation')
                            .annotator('addPlugin', 'Tags')
                            .annotator('addPlugin', 'Store',
                                    {
                                        prefix: '',

                                        annotationData: {
                                            'uri': '<your_url>'
                                        },

                                        loadFromSearch:
                                        {
                                            'limit': 0,
                                            'uri' : '<your_url>'
                                        },

                                        urls: {
                                            // These are the default URLs.
                                            create:  '{{ path("page_annotator_save") }}',
                                            update:  '{{ path("page_annotator_save") }}',
                                            destroy: '{{ path("page_annotator_delete") }}',
                                            search:  '{{ path("page_annotator_search", { 'uri' : '<your_url>' }) }}'
                                        }
                                    });


            // Set Annotator Language, for example Italian
            setAnnotatorLanguage("it");

            // Set fixed values
            var values = ["Tag1", "Tag2", "Tag3"];
            annotateWithFixedValues(values);
        });
        </script>
    </head>
        <body>
            <div id='container'>
                Lorem Ipsum è un testo segnaposto utilizzato nel settore della tipografia e della stampa. Lorem Ipsum è considerato il testo segnaposto standard sin dal sedicesimo secolo, quando un anonimo tipografo prese una cassetta di caratteri e li assemblò per preparare un testo campione. È sopravvissuto non solo a più di cinque secoli, ma anche al passaggio alla videoimpaginazione, pervenendoci sostanzialmente inalterato. Fu reso popolare, negli anni ’60, con la diffusione dei fogli di caratteri trasferibili “Letraset”, che contenevano passaggi del Lorem Ipsum, e più recentemente da software di impaginazione come Aldus PageMaker, che includeva versioni del Lorem Ipsum.
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

In your PHP code, yuo can manage annotations as entity object in this way:

``` php
    use Luperi\PageAnnotatorBundle\Controller\AnnotationController;
    
    class YourClassController extends Controller
    {
        public function YourAction()
        {
            $annotations = AnnotationController::getAll();
    
            return $this->render('YourBundle:YourClass:Your.html.twig', ['annotations' => $annotations]);
        }
    
    }
```

See `PageAnnotatorBundle/Entity/Annotation.php` for available methods

License
-------

This bundle is released under the MIT license. See the complete license in the
bundle:

    Resources/meta/LICENSE
