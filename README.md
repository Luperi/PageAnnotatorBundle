	
Installazione
=============

Passo 1: scaricare il bundle
----------------------------

Aprire il file '/composer.json' e aggiungere:

"require": 
  {
    "luperi/page_annotator_bundle": "dev-master"
  }

Passo 2: abilitare il bundle
----------------------------

Quindi, abilitare il bundle, aggiungendo la riga seguente nel file 'app/AppKernel.php' del progetto:

  new luperi\PageAnnotatorBundle\PageAnnotatorBundle(),
