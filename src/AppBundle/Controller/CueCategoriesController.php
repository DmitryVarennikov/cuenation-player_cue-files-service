<?php

namespace AppBundle\Controller;

use function GuzzleHttp\Psr7\parse_header;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
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
        $response = $cueCategoriesService->get();

        $content = json_decode($response->getBody()->getContents(), true);
        if (isset($content['_embedded']['cueCategories'])) {
            $resource = new Collection(
                $content['_embedded']['cueCategories'], function (array $cueCategory) {
                return $cueCategory;
            }, 'cueCategories'
            );

            if (!empty($response->getHeader('ETag'))) {
                $resource->setMetaValue('ETag', $response->getHeader('ETag')[0]);
            }

            $fractal = new Manager();
            $output = $fractal->createData($resource)->toArray();
        } else {
            $output = [];
        }

        return new JsonResponse($output);
    }

}