<?php

namespace TastPHP\Common;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TastPHP\Common\Kit\Validator;
use TastPHP\Framework\Event\AppEvent;
use TastPHP\Framework\Debug\Collector\VarCollector;
use TastPHP\Framework\Event\HttpEvent;

/**
 * Class Controller
 * @package TastPHP\Common
 */
class Controller
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * @param $name
     * @return mixed
     */
    protected function registerService($name)
    {
        return $this->container['service_kernel']->registerService($name);
    }

    /**
     * @param $html
     * @param array $parameters
     * @return Response
     */
    protected function render($html, $parameters = [])
    {
        if ($this->container['debug']) {
            $parameters['TwigPath'] = $html;
            if ($this->container->singleton('debugbar')->hasCollector('view')) {
                $this->container->singleton('debugbar')->getCollector('view')->setData($parameters);
            } else {
                $this->container->singleton('debugbar')->addCollector(new VarCollector($parameters));
            }
        }

        $content = $this->container['twig']->render($html, $parameters);
        return new Response($content, 200);
    }

    /**
     * @param string $name
     * @return null|callable
     */
    protected function get($name = '')
    {
        $name = (string)$name;

        if (empty($this->container[$name])) {
            return null;
        }

        return $this->container[$name];
    }

    /**
     * @param Request $request
     * @param array $verifyFields
     * @param bool $isValidate
     * @return bool
     */
    protected function checkFields(Request $request, array $verifyFields, $isValidate = false)
    {
        foreach ($verifyFields as $field) {
            if ('POST' == $request->getMethod()) {
                $fieldValue = $request->request->get($field);
                if (empty($fieldValue)) {
                    $this->json([
                        'msg' => "field {$field} doesn't exists!",
                        'code' => 400
                    ]);
                }
            }

            if ("GET" == $request->getMethod()) {
                $fieldValue = $request->query->get($field);
                if (empty($fieldValue)) {
                    $this->json([
                        'msg' => "field {$field} doesn't exists!",
                        'code' => 400
                    ]);
                }
            }

            if ($isValidate) {
                $result = Validator::$field($fieldValue);
                if (!$result) {
                    $this->json([
                        'msg' => "{$field} format error!",
                        'code' => 400
                    ]);
                }
            }
        }

        return false;
    }

    /**
     * Returns a RedirectResponse to the given URL.
     *
     * @param string $targetUrl The URL to redirect to
     * @param int $status The status code to use for the Response
     * @param array headers
     *
     * @return RedirectResponse
     */
    protected function redirect($targetUrl, $status = 302, $headers = array())
    {
        return new RedirectResponse($targetUrl, $status, $headers);
    }


    /**
     * @param $routeName    An route name
     * @param array $path An array of path parameters
     * @param array $query An array of query parameters
     * @throws callable|\Exception
     */
    protected function forward($routeName, array $path = [], array $query = [])
    {
        $allRoutes = $this->get('allRoutes');
        if (!$allRoutes[$routeName]) {
            throw new \Exception('routeName error.');
        }
        $routeConfig = $allRoutes[$routeName];
        $blankRoute = $this->get('blankRoute');
        $blankRoute->url = $routeConfig['pattern'];
        $blankRoute->config = $routeConfig['parameters'];
        $blankRoute->parameters = $path;
        $blankRoute->query = $query;
        $blankRoute->dispatch($this->container);
    }

    /**
     * dispatch jsonResponse content
     * @param $data
     * @param int $status
     * @param array $header
     */
    protected function json($data, $status = 200, array $header = [])
    {
        $this->get('eventDispatcher')->dispatch(AppEvent::RESPONSE, new HttpEvent(null, new JsonResponse($data, $status, $header)));
    }
}