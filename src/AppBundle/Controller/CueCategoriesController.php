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
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/cue-categories")
 */
class CueCategoriesController extends Controller
{

    /**
     * @Route("/", name="get_cue-categories")
     * @Method({"GET"})
     * @param Request $request
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $cueCategoriesService = $this->get('app.service.cuenation.cue_categories');

        $eTag = count($request->getETags()) ? $request->getETags()[0] : null;
        $httpResponse = $cueCategoriesService->get($eTag);


        $fractalManager = $this->get('league.fractal.manager');
        $response = new class($fractalManager) extends Response
        {

            protected function createResource() : ResourceInterface
            {
                $content = $this->getContent();
                $eTag = $this->getETag();

                // @TODO: 1. default `null` data is not working, `Scope::executeResourceTransformers` tries to iterate
                // over data in case of `Collection` resource
                // @TODO: 2. `null` transformer is not working either for the same reason
                $resource = new Collection($content['_embedded']['cueCategories'] ?? [], [$this, 'noneTransformer']);
                if ($eTag) {
                    $resource->setMetaValue('ETag', $eTag);
                }

                return $resource;
            }
        };

        return $response->return($httpResponse);
    }

}