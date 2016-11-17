<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Test;
use AppBundle\Zap\ZapService;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class TestController
 * @package AppBundle\Controller
 *
 * @author Pedro Carlos Abreu <pcabreus@gmail.com>
 */
class TestController extends ResourceController
{

    public function getSpiderScanStatusAction(Request $request)
    {
        $this->isGrantedOr403('show');
        /** @var Test $test */
        $test = $this->findOr404($request);

        $this->get('zap_service')->updateSpiderScanStatus($test);

        $view = $this
          ->view()
          ->setTemplate($this->config->getTemplate('show.html'))
          ->setTemplateVar($this->config->getResourceName())
          ->setData($test);

        return $this->handleView($view);
    }

    public function runAscanAction(Request $request)
    {
        $this->isGrantedOr403('show');
        /** @var Test $test */
        $test = $this->findOr404($request);

        $this->get('zap_service')->runAscan($test);

        $view = $this
          ->view()
          ->setTemplate($this->config->getTemplate('show.html'))
          ->setTemplateVar($this->config->getResourceName())
          ->setData($test);

        return $this->handleView($view);

    }

    public function getAscanStatusAction(Request $request)
    {
        $this->isGrantedOr403('show');
        /** @var Test $test */
        $test = $this->findOr404($request);

        $this->get('zap_service')->updateAscanStatus($test);

        $view = $this
          ->view()
          ->setTemplate($this->config->getTemplate('show.html'))
          ->setTemplateVar($this->config->getResourceName())
          ->setData($test);

        return $this->handleView($view);
    }

    public function reloadDatasAction(Request $request)
    {
        $this->isGrantedOr403('show');
        /** @var Test $test */
        $test = $this->findOr404($request);

        $this->get('zap_service')->reloadDatas($test);

        $view = $this
          ->view()
          ->setTemplate($this->config->getTemplate('show.html'))
          ->setTemplateVar($this->config->getResourceName())
          ->setData($test);

        return $this->handleView($view);
    }

    /**
     * Route("/api/test", name="api_test_index", methods={"GET", "POST"})
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function rapidAttackAction(Request $request)
    {
//        $zapService = new ZapService("45fi5ccjibmapljdbi3ahf2p60");

//        $this->container->get('validator')->validate($url, )
        //@TODO Validar la entrada

//        if ($request->isMethod('GET')) {
        $url = $request->get('test');

//            $zapService->scan($url);
        return new JsonResponse($url);
//        } else {
//            return new JsonResponse('Error', JsonResponse::HTTP_NOT_FOUND);
//        }

    }
} 