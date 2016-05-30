<?php

declare(strict_types = 1);

namespace AppBundle\Service\CueNation;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Psr7;

class CueCategories
{

    private $environment;
    private $httpClient;

    public function __construct(string $environment, HttpClient $httpClient)
    {
        $this->environment = $environment;
        $this->httpClient = $httpClient;
    }

    public function get(string $eTag = null): Psr7\Response
    {
        if ('test' === $this->environment) {
            return $this->testGet($eTag);
        }

        $headers = [];
        if ($eTag !== null) {
            $headers['If-None-Match'] = $eTag;
        }

        $request = new Psr7\Request('GET', 'cue-categories', $headers);
        $response = $this->httpClient->send($request);

        return $response;
    }

    private function testGet(string $eTag = null): Psr7\Response
    {
        $headers = [
            'Content-Type' => 'application/hal+json',
            'ETag' => 'this is a test ETag',
        ];
        $body = [
            '_likns' => [
                'self' => [
                    'href' => [
                        'http://test-server/cue-categories',
                    ],
                ],
            ],
            '_embedded' => [
                'cueCategories' => [
                    [
                        'id' => '53f24f73cb88814e672ce4f0',
                        'name' => '#goldrushRADIO',
                        'host' => 'with Ben Gold',
                        'link' => 'http://cuenation.com/?page=cues&folder=goldrushradio',
                    ],
                ],
            ],
        ];

        return new Psr7\Response(200, $headers, json_encode($body));
    }

}