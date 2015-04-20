<?php

namespace Luperi\PageAnnotatorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Luperi\PageAnnotatorBundle\Entity\Annotation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AnnotationController extends Controller
{
    public function saveAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager('annotation');
        $json = $request->getContent();

        if(!$json){
            return new JsonResponse(array('success' => false));
        }

        $json = json_decode($json, true);
        $range = $json['ranges'][0];
        $search_array = array(
            'url' => $json['uri'],
            'start' => $range['start'],
            'startOffset' => $range['startOffset'],
            'end' => $range['end'],
            'endOffset' => $range['endOffset']
        );

        $annotation = $em->getRepository('PageAnnotatorBundle:Annotation', 'annotation')->findOneBy($search_array);

        if(!$annotation)
            $annotation = new Annotation();

        $annotation->setStart($range['start']);
        $annotation->setStartOffset($range['startOffset']);
        $annotation->setEnd($range['end']);
        $annotation->setEndOffset($range['endOffset']);
        $annotation->setUrl($json['uri']);
        $annotation->setQuote($json['quote']);
        $annotation->setText($json['text']);
        $em->persist($annotation);
        $em->flush();
        return new JsonResponse(array('id' => $annotation->getId()));
    }

    public function deleteAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager('annotation');
        $json = $request->getContent();

        if(!$json){
            return new JsonResponse(array('success' => false));
        }

        $json = json_decode($json, true);
        $range = $json['ranges'][0];
        $search_array = array(
            'url' => $json['uri'],
            'start' => $range['start'],
            'startOffset' => $range['startOffset'],
            'end' => $range['end'],
            'endOffset' => $range['endOffset']
        );

        $annotation = $em->getRepository('PageAnnotatorBundle:Annotation', 'annotation')->findOneBy($search_array);

        if(!$annotation)
            return new JsonResponse(array('success' => false));

        $em->remove($annotation);
        $em->flush();

        return new JsonResponse(array('success' => true));
    }

    public function searchAction(Request $request)
    {
        $uri = $request->get('uri');

        if(!$uri){
            return new JsonResponse(array('success' => false));
        }

        $em = $this->get('doctrine')->getManager('annotation');
        $annotations = $em->getRepository('PageAnnotatorBundle:Annotation', 'annotation')
            ->findBy(array('url' => $uri));

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

        return new Response(json_encode($annotation_array));
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

    public function libraryJsAction() {
        $params = array(
            'deleteAll' => $this->generateUrl('page_annotator_delete_all'),
            'deleteAllByUrl' => $this->generateUrl('page_annotator_delete_all_by_url'),
            'save' => $this->generateUrl('page_annotator_save'),
            'delete' => $this->generateUrl('page_annotator_delete'),
            'delete' => $this->generateUrl('page_annotator_delete'),
          );
        $rendered = $this->renderView( 'PageAnnotatorBundle:JSLibrary:Luperi-annotatorjs.js.twig', $params );
        $response = new Response( $rendered );
        $response->headers->set( 'Content-Type', 'text/javascript' );
        return $response;
    }

}