<?php
/**
 * @package     Swift
 * @copyright   Copyright (c) 2011 Axel ETCHEVERRY (http://www.axel-etcheverry.com)
 * @license     GPL
 * @version     $Id: Request.php,v1.0 28 mars 2011 12:56:28 euskadi31 $;
 */

namespace Swift\Rest
{
    require_once __DIR__ . '/RequestInterface.php';

    class Request implements RequestInterface
    {
        const FORMAT_JSON   = 'json';
        const FORMAT_XML    = 'xml';

        /**
         * Request headers
         * @var array
         */
        protected $_headers = array();

        /**
         * Singleton instance
         *
         * @var Swift\Rest\Request
         */
        protected static $_instance = null;

        /**
         *
         * @var string
         */
        protected $_uri;

        /**
         * @var string
         */
        protected $_method;

        /**
         * Request params
         * @var array
         */
        protected $_params = array();

        /**
         * Returns an instance of Swift\Rest\Request
         *
         * Singleton pattern implementation
         *
         * @return Swift\Rest\Request Provides a fluent interface
         */
        public static function getInstance()
        {
            if (null === self::$_instance) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }

        protected function __construct()
        {
            // ici parce header and request

            $this->_uri = $_SERVER['REQUEST_URI'];
            $this->_method = $_SERVER['REQUEST_METHOD'];

            $pos = strpos($this->_uri, '?');

            if($pos) {
                $this->_uri = substr($this->_uri, 0, $pos);
            }

            if (function_exists('apache_request_headers')) {
                foreach(apache_request_headers() as $key => $value) {
                    $this->_headers[strtolower($key)] = $value;
                }
            }

            if(isset($_GET)) {
                $this->setParams($_GET);
            }

            if(isset($_POST)) {
                $this->setParams($_POST);
            }
        }

        /**
         * Add a new param
         * @param string $name
         * @param string|int $value
         * @return Swift\Rest\Request
         */
        public function addParam($name, $value)
        {
            $this->_params[$name] = $value;
            return $this;
        }

        /**
         * Set params
         * @param string $params
         * @return Swift\Rest\Request
         */
        public function setParams($params)
        {
            if(is_array($params)) {
                foreach($params as $name => $value) {
                    $this->addParam($name, $value);
                }
            }

            return $this;
        }

        /**
         * Get request params
         * @return array
         */
        public function getParams()
        {
            return $this->_params;
        }

        /**
         * Get request param by name
         * @param string $name
         * @return string|bool
         */
        public function getParam($name)
        {
            if(isset($this->_params[$name])) {
                return $this->_params[$name];
            } else {
                return false;
            }
        }

        /**
         * Get http method
         * @return string
         */
        public function getMethod()
        {
            return $this->_method;
        }

        /**
         * Get request headers
         * @return array
         */
        public function getHeaders()
        {
            return $this->_headers;
        }

        /**
         * Get request header by name
         * @param string $name
         * @return string|null
         */
        public function getHeader($name)
        {
            if(isset($this->_headers[$name])) {
                return $this->_headers[$name];
            }

            return null;
        }

        /**
         * Get request uri
         * @return string
         */
        public function getUri()
        {
            return $this->_uri;
        }

        /**
         * Get format
         */
        public function getFormat()
        {
            $accept = $this->getHeader('accept');

            if(empty($accept)) {
                return self::FORMAT_JSON;
            } else {
                switch($accept) {
                    case 'application/xml':
                        return self::FORMAT_XML;
                        break;
                    case 'text/json':
                    case 'application/json':
                        return self::FORMAT_JSON;
                        break;
                }
            }
        }
    }
}