<?php
/**
 * @package     Swift
 * @author		Axel ETCHEVERRY <axel@etcheverry.biz>
 * @copyright   Copyright (c) 2011 Axel ETCHEVERRY (http://www.axel-etcheverry.com)
 * Displays		<a href="http://creativecommons.org/licenses/MIT/deed.fr">MIT</a>
 * @license		http://creativecommons.org/licenses/MIT/deed.fr	MIT
 * @version     $Id: Request.php,v1.0 28 mars 2011 12:56:28 euskadi31 $;
 */

namespace Swift\Rest
{
    require_once __DIR__ . '/RequestInterface.php';

    class Request implements RequestInterface
    {
        const FORMAT_JSON   = 'json';
        const FORMAT_XML    = 'xml';
		const FORMAT_AMF	= 'amf';

		/**
		 * @var array
		 */
		public static $formats = array(
			self::FORMAT_JSON,
			self::FORMAT_XML,
			self::FORMAT_AMF
		);

        /**
         * Request headers
		 *
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
         * Request params
		 *
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
		
		/**
		 * Init Request
		 * 
		 * @return Swift\Rest\Request
		 */
        protected function __construct()
        {
            // ici parce header and request

            $this->_uri = $_SERVER['REQUEST_URI'];

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

			$method = $this->getMethod();
			
			if ($method == 'PUT' || $method == 'POST') {
				$data = file_get_contents('php://input');
				parse_str($data, $data);
				$this->setParams($data);
				unset($data);
			}
        }

        /**
         * Add a new param
		 *
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
		 *
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
		 *
         * @return array
         */
        public function getParams()
        {
            return $this->_params;
        }

        /**
         * Get request param by name
		 *
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
		 *
         * @return string
         */
        public function getMethod()
        {
			$method = $_SERVER['REQUEST_METHOD'];
			$override = null;
			
			if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
				$override = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
			} elseif (isset($_GET['method'])) {
				$override = $_GET['method'];
			}
			
			if ($method == "POST" && strtoupper($override) == "PUT") {
				$method = "PUT";
			} elseif ($method == "POST" && strtoupper($override) == "DELETE") {
				$method = "DELETE";
			}
			
			return $method;
        }

        /**
         * Get request headers
		 *
         * @return array
         */
        public function getHeaders()
        {
            return $this->_headers;
        }

        /**
         * Get request header by name
		 *
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
		 *
         * @return string
         */
        public function getUri()
        {
            return $this->_uri;
        }

        /**
         * Get accept format
		 *
		 * @return string 
         */
        public function getFormat()
        {
			$format = self::FORMAT_JSON;
			$accept = explode(',', $_SERVER['HTTP_ACCEPT']);
			
			if (in_array(self::FORMAT_JSON, $accept)) {
				$format = self::FORMAT_JSON;
			} elseif (in_array(self::FORMAT_XML, $accept)) {
				$format = self::FORMAT_XML;
			}
			
			return $format;
        }
    }
}