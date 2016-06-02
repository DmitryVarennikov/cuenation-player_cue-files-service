<?php

namespace AppBundle\Presentation;

use GuzzleHttp\Psr7;
use League\Fractal\Manager;
use League\Fractal\Resource\ResourceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class Response
{

    /**
     * @var Manager
     */
    private $fractalManager;

    /**
     * @var Psr7\Response
     */
    private $response;

    public function __construct(Manager $fractalManager)
    {
        $this->fractalManager = $fractalManager;
    }

    public function return (Psr7\Response $response) : JsonResponse
    {
        $this->response = $response;

        $resource = $this->createResource();
        $output = $this->fractalManager->createData($resource)->toArray();

        return new JsonResponse($output);
    }

    abstract protected function createResource() : ResourceInterface;

    protected function getContent()
    {
        return json_decode($this->response->getBody()->getContents(), true);
    }

    protected function getETag()
    {
        $return = null;

        if (!empty($this->response->getHeader('ETag'))) {
            $return = $this->response->getHeader('ETag')[0];
        }

        return $return;
    }

    public function noneTransformer($data)
    {
        return $data;
    }

}