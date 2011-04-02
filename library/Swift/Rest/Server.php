<?php
/**
 * @package     Swift
 * @copyright   Copyright (c) 2011 Axel ETCHEVERRY (http://www.axel-etcheverry.com)
 * @license     GPL
 * @version     $Id: Server.php,v1.0 28 mars 2011 12:14:49 euskadi31 $;
 */

namespace Swift\Rest
{
    require_once __DIR__ . '/Request.php';
    require_once __DIR__ . '/Router.php';
    require_once __DIR__ . '/Response.php';

    use Swift\Exception;



    class Server
    {

        const VERSION = '0.1';

        /**
         * Request
         * @var Swift\Rest\Request
         */
        protected $_request;

        /**
         * Response
         * @var Swift\Rest\Response
         */
        protected $_response;

        /**
         * Router
         * @var Swift\Rest\Router
         */
        protected $_router;

        /**
         * Init Server
         * @param array $routes
         * @return Swift\Rest\Server
         */
        public function __construct($routes)
        {
            $this->_request = Request::getInstance();

            $this->_router = new Router($this->_request);
            $this->_router->setRoutes($routes);
            unset($routes);

            $this->_response = new Response();
            $this->_response->setHeader('X-Powered-By', 'PHP/Swift ' . self::VERSION, true);
        }

        /**
         *
         */
        public function handle()
        {
            $route = $this->_router->getRoute();
            $request = $this->_router->getRequest();

            if(is_object($route)) {
                $controllerClassName = $route->getController();

                if(class_exists($controllerClassName)) {
                    $controller = new $controllerClassName($request, $this->_response);
                    $controller->dispatch($request->getMethod());
                    $controller->getResponse()->sendResponse();
                } else {
                    throw new Exception(sprintf("Resource '%s' not found !", $route->getUri()), 404);
                }
            } else {
                throw new Exception(sprintf("Resource '%s' not found !", $this->_request->getUri()), 404);
            }
        }
    }
}