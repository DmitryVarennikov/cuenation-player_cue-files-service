<?php

namespace AppBundle\Controller;

use AppBundle\Presentation\Response;
use function GuzzleHttp\Psr7\parse_header;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\ResourceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/cue-categories")
 */
class CueCategoriesController extends Controller
{

    /**
     * @Route("/", name="get_cue-categories")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $cueCategoriesService = $this->get('app.service.cuenation.cue_categories');
        $httpResponse = $cueCategoriesService->get();


        $fractalManager = $this->get('league.fractal.manager');
        $response = new class($fractalManager) extends Response
        {

            protected function createResource() : ResourceInterface
            {
                $content = $this->getContent();
                $eTag = $this->getETag();

                $resource = new Collection($content['_embedded']['cueCategories'] ?? null, [$this, 'noneTransformer']);
                if ($eTag) {
                    $resource->setMetaValue('ETag', $eTag);
                }

                return $resource;
            }
        };

        return $response->return($httpResponse);
    }

}