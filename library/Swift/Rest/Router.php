<?php
/**
 * @package     Swift
 * @author      Axel ETCHEVERRY <axel@etcheverry.biz>
 * @copyright   Copyright (c) 2011 Axel ETCHEVERRY (http://www.axel-etcheverry.com)
 * Displays     <a href="http://creativecommons.org/licenses/MIT/deed.fr">MIT</a>
 * @license     http://creativecommons.org/licenses/MIT/deed.fr    MIT
 */

namespace Swift\Rest
{
    require_once __DIR__ . '/RequestInterface.php';
    require_once __DIR__ . '/Request.php';

    use Swift\Rest\Router\Route;

    class Router
    {
        /**
         * @var Swift\Rest\RequestInterface
         */
        protected $_request;

        /**
         * @var array
         */
        protected $_routes = array();

        /**
         * @var string
         */
        protected $_route;

        /**
         * Init Router
         * @param Swift\Rest\Request|null $request
         * @return Swift\Rest\Router
         */
        public function __construct($request = null)
        {
            if(empty($request)) {
                $this->_request = Request::getInstance();
            } else {
                $this->_request = $request;
                unset($request);
            }
        }

        /**
         * Add rule
         * @param string $rule
         * @param string $controller
         * @param array $regexs
         * @return Swift\Rest\Router
         */
        public function addRule($rule, $controller, $regexs = array())
        {
            $this->_routes[$rule] = new Route(
                $rule,
                $this->_request,
                $controller,
                $regexs
            );
            return $this;
        }

        /**
         * Set routes
         * @param array $routes
         * @return Swift\Rest\Router
         */
        public function setRoutes($routes)
        {
            if(is_array($routes)) {
                foreach($routes as $rule => $controller) {
                    $this->addRule($rule, $controller);
                }
            }

            return $this;
        }

        /**
         * Get route
         * @return bool|Swift\Rest\Router\Route
         */
        public function getRoute()
        {
            foreach($this->_routes as $route) {
                if($route->isMatched()) {
                    $this->_route = $route;
                    return $this->_route;
                }
            }

            return false;
        }

        /**
         * Set request
         * @param Swift\Rest\RequestInterface $request
         * @return Swift\Rest\Responce
         */
        public function setRequest(RequestInterface $request)
        {
            $this->_request = $request;
            return $this;

        }

        /**
         * Get request
         * @return Swift\Rest\RequestInterface
         */
        public function getRequest()
        {
            if(is_object($this->_request)) {
                return $this->_request;
            } else {
                $this->_request = Request::getInstance();
                return $this->_request;
            }

        }

    }
}