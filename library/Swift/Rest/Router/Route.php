<?php
/**
 * @package     Swift
 * @author      Axel ETCHEVERRY <axel@etcheverry.biz>
 * @copyright   Copyright (c) 2011 Axel ETCHEVERRY (http://www.axel-etcheverry.com)
 * Displays     <a href="http://creativecommons.org/licenses/MIT/deed.fr">MIT</a>
 * @license     http://creativecommons.org/licenses/MIT/deed.fr    MIT
 */

namespace Swift\Rest\Router
{
    use Swift\Rest\RequestInterface;

    class Route
    {
        /**
         * @var bool
         */
        protected $_isMatched = false;

        /**
         * Url params
         * @var array
         */
        protected $_params = array();

        /**
         * Url
         * @var string
         */
        protected $_url;

        /**
         * Regex for validate params
         * @var array
         */
        protected $_regexs = array();

        /**
         * Controller
         * @var string
         */
        protected $_controller;

        /**
         * Request
         * @var Swift\Rest\Request
         */
        protected $_request;

        /**
         * Init new route
         *
         * @param string $url
         * @param Swift\Rest\RequestInterface $request
         * @param string $controller
         * @param array $regexs
         */
        public function __construct($url, RequestInterface $request, $controller, array $regexs = array())
        {
            $this->_url = $url;
            $this->_regexs = $regexs;
            $this->_controller = $controller;
            $this->_request = $request;

            $p_names = array();
            $p_values = array();

            $lastUrlRegex = preg_replace_callback('@:[\w]+$@', function($matches) use ($regexs) {
                $key = str_replace(':', '', $matches[0]);

                if (array_key_exists($key, $regexs)) {
                    return '(?P<' . $key . '>' . $regexs[$key] . '|*)';
                } else {
                    return '(?P<' . $key . '>[a-zA-Z0-9_\+\-%]*)';
                }
            }, $url);

            preg_match_all('@:([\w][^\/]+)@', $url, $p_names, PREG_PATTERN_ORDER);
            $p_names = $p_names[0];

            $urlRegex = preg_replace_callback('@:[\w][^\/]+@', function($matches) use ($regexs) {
                $key = str_replace(':', '', $matches[0]);

                if (array_key_exists($key, $regexs)) {
                    return '(?P<' . $key . '>' . $regexs[$key] . ')';
                } else {
                    return '(?P<' . $key . '>[a-zA-Z0-9_\+\-%]+)';
                }
            }, $lastUrlRegex);
            unset($lastUrlRegex);

            $urlRegex .= '/?';

            if(preg_match('@^' . $urlRegex . '$@', $this->_request->getUri(), $p_values)) {
                array_shift($p_values);

                foreach($p_names as $index => $value) {
                    $this->_params[substr($value, 1)] = urldecode($p_values[$index]);
                }

                $this->_params = array_filter($this->_params);
                $this->_request->setParams($this->_params);

                $this->_isMatched = true;
            }

            unset($p_names, $p_values, $urlRegex);
        }

        /**
         * Get all params
         * @return array
         */
        public function getParams()
        {
            return $this->_params;
        }

        /**
         * Check is route matched
         * @return bool
         */
        public function isMatched()
        {
            return $this->_isMatched;
        }

        /**
         * Get controller
         * @return string
         */
        public function getController()
        {
            return $this->_controller;
        }

        /**
         * Get request uri
         * @return string
         */
        public function getUri()
        {
            return $this->_request->getUri();
        }
    }
}