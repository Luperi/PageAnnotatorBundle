<?php

namespace Luperi\PageAnnotatorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Luperi\PageAnnotatorBundle\Entity\Annotation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AnnotationController extends Controller
{
    /*
     * Cerca una Annotation con le caratteristiche di quella che deve essere salvata
     * In caso non esista già, ne viene istanziata una nuova e salvata nel DB
     * Ciò consente di usare questa funzione sia per l'update che per l'inserimento di una nuova Annotation
     * Inoltre evita che vengano salvate istanze duplicate anche in caso di Annotation creata sulla
     * stessa porzione di pagina
     */
    public function saveAction(Request $request)
    {
        if ($request->get('start') == null
            || $request->get('startOffset') == null
            || $request->get('end') == null
            || $request->get('endOffset') == null
            || $request->get('quote') == null
            || $request->get('url') == null
            || $request->get('text') == null)
            return new JsonResponse(array('success' => false));
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
        $annotation = $em->getRepository('PageAnnotatorBundle:Annotation', 'annotation')->findOneBy($search_array);

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

        return new JsonResponse(array('id' => $annotation->getId()));
    }

    public function deleteAction(Request $request)
    {
        if ($request->get('start') == null
            || $request->get('startOffset') == null
            || $request->get('end') == null
            || $request->get('endOffset') == null
            || $request->get('url') == null)
            return new JsonResponse(array('success' => false));
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
        $annotation = $em->getRepository('PageAnnotatorBundle:Annotation', 'annotation')->findOneBy($search_array);

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
        $annotations = $em->getRepository('PageAnnotatorBundle:Annotation', 'annotation')->findAll();
        foreach($annotations as $annotation){
            $em->remove($annotation);
            $em->flush();
        }

        return new JsonResponse(array('success' => true));
    }

    public function deleteAllByUrlAction(Request $request)
    {
        if ($request->get('url') == null)
            return new JsonResponse(array('success' => false));

        $em = $this->get('doctrine')->getManager('annotation');
        $annotations = $em->getRepository('PageAnnotatorBundle:Annotation', 'annotation')->findBy(
            array('url' => $request->get('url'))
        );
        foreach($annotations as $annotation){
            $em->remove($annotation);
            $em->flush();
        }

        return new JsonResponse(array('success' => true));
    }

    public function getAll()
    {
        return $this->getDoctrine()
            ->getRepository('PageAnnotatorBundle:Annotation', 'annotation')
            ->findAll();
    }

    public function searchAction(){
        $annotations = AnnotationController::getAll();

        $annotation_array = array('response' => 'No results found.');
        if($annotations){
            $annotation_data = array();
            foreach($annotations as $annotation){
                $range = array(
                    'start' => $annotation->getStart(),
                    'end' => $annotation->getEnd(),
                    'startOffset' => $annotation->getStartOffset(),
                    'endOffset' => $annotation->getEndOffset(),
                );
                $annotation_info = array(
                    'quote' => $annotation->getQuote(),
                    'text' => $annotation->getText(),
                    'ranges' => array($range)
                );
                $annotation_data[] = $annotation_info;
            }
            $annotation_array = array('total' => count($annotation_data), 'rows' => $annotation_data);
        }
        return new Response(stripslashes(json_encode($annotation_array)));
    }

}