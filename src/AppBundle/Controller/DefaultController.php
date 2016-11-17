<?php

namespace AppBundle\Controller;

use AppBundle\Zap\ZapService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Zap\Zapv2;

class DefaultController extends Controller
{
    /**
     * @Route("/api/views/{template}", name="api_views")
     *
     * @param Request $request
     * @param $template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function templateAction(Request $request, $template)
    {
        return $this->render(
          'api/templates/' . $template
        );
    }


    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
//        $zapService = new ZapService("45fi5ccjibmapljdbi3ahf2p60");
//
//        $zapService->scan('http://localhost/resources', date('Y-m-d H:i:s'));

        // replace this example code with whatever you need
        return $this->render(
          'default/index.html.twig',
          array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..'),
          )
        );
    }

    /**
     * @Route("/alerts", name="alerts")
     */
    public function alertAction(Request $request)
    {
        $zapService = new ZapService($this->get('app.manager.test'), "45fi5ccjibmapljdbi3ahf2p60");
//
//        $zapService->alerts();

        // replace this example code with whatever you need
        return $this->render(
          'default/index.html.twig',
          array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..'),
          )
        );
    }

    /**
     * @Route("/rapid_attack", name="rapid_attack")
     */
    public function rapidAttackAction(Request $request)
    {
        $zapService = new ZapService($this->get('app.manager.test'), "45fi5ccjibmapljdbi3ahf2p60");

//        $this->container->get('validator')->validate($url, )
        //@TODO Validar la entrada

        if ($request->isMethod('POST')) {
            $url = $request->get('url');

//            $zapService->scan($url);
            return new JsonResponse($url);
        } else {
            return new JsonResponse('Error', JsonResponse::HTTP_NOT_FOUND);
        }

    }
}
