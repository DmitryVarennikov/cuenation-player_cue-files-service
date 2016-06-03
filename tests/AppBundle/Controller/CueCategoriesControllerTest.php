<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 * @group functional
 */
class CueCategoriesControllerTest extends WebTestCase
{

    /**
     * @var Client
     */
    private $client;

    protected function setUp()
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    /**
     * @test
     */
    public function index()
    {
        $this->client->request('GET', '/cue-categories/');
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains('json', $response->headers->get('Content-Type'));

        $content = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('ETag', $content['meta']);
        $this->assertNotEmpty($content['data']);

        return $content['meta']['ETag'];
    }

    /**
     * @test
     * @depends index
     */
    public function indexWithETag($eTag)
    {
        $this->client->request('GET', '/cue-categories/', [], [], ['HTTP_If-None-Match' => $eTag]);
        $response = $this->client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());

        $content = json_decode($response->getContent(), true);
        $this->assertSame($eTag, $content['meta']['ETag']);
        $this->assertEmpty($content['data']);
    }

}
