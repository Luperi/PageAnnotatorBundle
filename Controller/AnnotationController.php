<?php

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Luperi\PageAnnotatorBundle\Entity\Annotation;
use Symfony\Component\HttpFoundation\JsonResponse;

class AnnotationController extends Controller
{
    /*
     * Cerca una Annotation con le caratteristiche di quella che deve essere salvata
     * In caso non esista già ne viene istanziata una nuova e salvata nel DB
     * Ciò consente di usare questa funzione sia per l'update che per l'inserimento di una nuova Annotation
     * Inoltre evita che vengano salvate istanze duplicate anche in caso di Annotation creata sulla
     * stessa porzione di pagina
     */
    public function saveAction(Request $request)
    {
        $a = [];
        $a['start'] = $request->get('start');
        $a['startOffset'] = $request->get('startOffset');
        $a['end'] = $request->get('end');
        $a['endOffset'] = $request->get('endOffset');
        $a['quote'] = $request->get('quote');
        $a['url'] = $request->get('url');
        $a['text'] = $request->get('text');

        $search_array = array(
            'url' => $a['url'],
            'start' => $a['start'],
            'startOffset' => $a['startOffset'],
            'end' => $a['end'],
            'endOffset' => $a['endOffset']
        );

        $em = $this->get('doctrine.orm.annotation_entity_manager');
        $annotation = $em->getRepository('LuperiPageAnnotatorBundle:Annotation', 'annotation')->findOneBy($search_array);

        if(!$annotation){
            $annotation = new Annotation();
        }

        $annotation->setStart($a['start']);
        $annotation->setStartOffset($a['startOffset']);
        $annotation->setEnd($a['end']);
        $annotation->setEndOffset($a['endOffset']);
        $annotation->setQuote($a['quote']);
        $annotation->setUrl($a['url']);
        $annotation->setText($a['text']);

        $em->persist($annotation);
        $em->flush();

        echo "ciao";

        return new JsonResponse(array('id' => $annotation->getId()));
    }

    public function deleteAction(Request $request)
    {
        $a = [];
        $a['url'] = $request->get('url');
        $a['start'] = $request->get('start');
        $a['startOffset'] = $request->get('startOffset');
        $a['end'] = $request->get('end');
        $a['endOffset'] = $request->get('endOffset');

        $search_array = array(
            'url' => $a['url'],
            'start' => $a['start'],
            'startOffset' => $a['startOffset'],
            'end' => $a['end'],
            'endOffset' => $a['endOffset']
        );

        $em = $this->get('doctrine')->getManager('annotation');
        $annotation = $em->getRepository('LuperiPageAnnotatorBundle:Annotation', 'annotation')->findOneBy($search_array);

        if(!$annotation){
            return new JsonResponse(array('success' => false));
        }

        $em->remove($annotation);
        $em->flush();

        return new JsonResponse(array('success' => true));
    }

    public function deleteAllAction()
    {
        $em = $this->get('doctrine')->getManager('annotation');
        $annotations = $em->getRepository('LuperiPageAnnotatorBundle:Annotation', 'annotation')->findAll();
        foreach($annotations as $annotation){
            $em->remove($annotation);
            $em->flush();
        }

        return new JsonResponse(array('success' => true));
    }

}