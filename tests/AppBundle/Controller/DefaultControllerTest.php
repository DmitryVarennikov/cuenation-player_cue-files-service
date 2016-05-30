<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{

    /**
     * @test
     */
    public function index()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('json', $response->headers->get('Content-Type'));

        $content = json_decode($response->getContent(), true);
        $this->assertSame('Feeling good!', $content['status']);
    }
    
}

