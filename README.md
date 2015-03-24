Installazione
=============

Passo 1: scaricare il bundle
----------------------------

Aprire una console, entrare nella cartella del progetto ed eseguire il
comando seguente per scaricare l'ultima versione stabile di questo bundle:

```bash
"require": {
    "luperi/page_annotator_bundle": "dev-master"
}
```

Passo 2: abilitare il bundle
----------------------------

Quindi, abilitare il bundle, aggiungendo la riga seguente nel file `app/AppKernel.php`
del progetto:

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
