<?php

namespace Luperi\PageAnnotatorBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AnnotationControllerTest extends WebTestCase
{
    public function testSave()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/save');
    }

    public function testDelete()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/delete');
    }

    public function testDeleteall()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/deleteAll');
    }

}
