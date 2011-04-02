<?php
/**
 * @package     Swift
 * @author		Axel ETCHEVERRY <axel@etcheverry.biz>
 * @copyright   Copyright (c) 2011 Axel ETCHEVERRY (http://www.axel-etcheverry.com)
 * Displays		<a href="http://creativecommons.org/licenses/MIT/deed.fr">MIT</a>
 * @license		http://creativecommons.org/licenses/MIT/deed.fr	MIT
 * @version     $Id: Response.php,v1.0 28 mars 2011 12:27:44 euskadi31 $;
 */

namespace Swift\Rest
{
    require_once __DIR__ . '/RequestInterface.php';
    require_once __DIR__ . '/Request.php';


    use Swift\Rest\Response\Json,
        Swift\Rest\Response\Xml,
		Swift\Rest\Response\Amf,
        Swift\Exception;

    class Response
    {
        /**
         * HTTP response code to use in headers
		 *
         * @var int
         */
        protected $_httpResponseCode = 200;

        /**
         *
         * @var Swift\Rest\RequestInterface
         */
        protected $_request;

        /**
         * Response headers
		 *
         * @var array
         */
        protected $_headers = array();

        /**
         *
         * @var array
         */
        protected $_data = array();

        /**
         * Flag; if true, when header operations are called after headers have been
         * sent, an exception will be raised; otherwise, processing will continue
         * as normal. Defaults to true.
         *
         * @see canSendHeaders()
         * @var boolean
         */
        public $headersSentThrowsException = true;


        /**
         * Response charset
         *
         * @var string
         */
        protected $_charset = 'UTF-8';

		/**
	     *
	     * @var array
	     */
	    public static $httpResponses = array(
	        // Informational 1xx
	        100 => 'Continue',
	        101 => 'Switching Protocols',

	        // Success 2xx
	        200 => 'OK',
	        201 => 'Created',
	        202 => 'Accepted',
	        203 => 'Non-Authoritative Information',
	        204 => 'No Content',
	        205 => 'Reset Content',
	        206 => 'Partial Content',

	        // Redirection 3xx
	        300 => 'Multiple Choices',
	        301 => 'Moved Permanently',
	        302 => 'Found',  // 1.1
	        303 => 'See Other',
	        304 => 'Not Modified',
	        305 => 'Use Proxy',
	        // 306 is deprecated but reserved
	        307 => 'Temporary Redirect',

	        // Client Error 4xx
	        400 => 'Bad Request',
	        401 => 'Unauthorized',
	        402 => 'Payment Required',
	        403 => 'Forbidden',
	        404 => 'Not Found',
	        405 => 'Method Not Allowed',
	        406 => 'Not Acceptable',
	        407 => 'Proxy Authentication Required',
	        408 => 'Request Timeout',
	        409 => 'Conflict',
	        410 => 'Gone',
	        411 => 'Length Required',
	        412 => 'Precondition Failed',
	        413 => 'Request Entity Too Large',
	        414 => 'Request-URI Too Long',
	        415 => 'Unsupported Media Type',
	        416 => 'Requested Range Not Satisfiable',
	        417 => 'Expectation Failed',

	        // Server Error 5xx
	        500 => 'Internal Server Error',
	        501 => 'Not Implemented',
	        502 => 'Bad Gateway',
	        503 => 'Service Unavailable',
	        504 => 'Gateway Timeout',
	        505 => 'HTTP Version Not Supported',
	        509 => 'Bandwidth Limit Exceeded'
	    );

        /**
         *
         * @param array|null $data
         */
        public function __construct($data = null)
        {
            if(!empty($data)) {
                $this->_data = $data;
            }
        }

        /**
         * Set HTTP response code to use with headers
         *
         * @param int $code
         * @return Swift\Rest\Responce
         */
        public function setHttpResponseCode($code)
        {
            if (!is_int($code) || (100 > $code) || (599 < $code)) {
                throw new Exception('Invalid HTTP response code', 500);
            }

            $this->_httpResponseCode = (int)$code;
            return $this;
        }

        /**
         * Retrieve HTTP response code
         *
         * @return int
         */
        public function getHttpResponseCode()
        {
            return $this->_httpResponseCode;
        }

        /**
         * Set response data
         *
         * @param array $data
         * @return Swift\Rest\Responce
         */
        public function setData(array $data)
        {
            $this->_data = $data;
            return $this;
        }

        /**
         * Normalize a header name
         *
         * Normalizes a header name to X-Capitalized-Names
         *
         * @param  string $name
         * @return string
         */
        protected function _normalizeHeader($name)
        {
            $filtered = str_replace(array('-', '_'), ' ', (string) $name);
            $filtered = ucwords(strtolower($filtered));
            $filtered = str_replace(' ', '-', $filtered);
            return $filtered;
        }

        /**
         * Set a header
         *
         * If $replace is true, replaces any headers already defined with that
         * $name.
         *
         * @param string $name
         * @param string $value
         * @param boolean $replace
         * @return Swift\Rest\Responce
         */
        public function setHeader($name, $value, $replace = false)
        {
            $this->canSendHeaders(true);
            $name  = $this->_normalizeHeader($name);
            $value = (string) $value;

            if ($replace) {
                foreach ($this->_headers as $key => $header) {
                    if ($name == $header['name']) {
                        unset($this->_headers[$key]);
                    }
                }
            }

            $this->_headers[] = array(
                'name'    => $name,
                'value'   => $value,
                'replace' => $replace
            );

            return $this;
        }

        /**
         * Set response headers
         *
         * @param array $headers
         * @return Swift\Rest\Responce
         */
        public function setHeaders($headers)
        {
            $this->canSendHeaders(true);

            if(is_array($headers)) {
                foreach($headers as $herder) {
                    $this->setHeader(
                        $header['name'],
                        $header['value'],
                        (isset($header['replace'])) ? $header['replace'] : false
                    );
                }
            }

            return $this;
        }

        /**
         * Get response headers
         *
         * @return array
         */
        public function getHeaders()
        {
            return $this->_headers;
        }

        /**
         * Get response header by name
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
         * Set request
         *
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
         *
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

        /**
         * Render response
         *
         * @param array $data
         * @return string
         */
        public function render(array $data)
        {
			switch($this->getRequest()->getFormat()) {
				case Request::FORMAT_XML:
					$this->setHeader(
	                    'Content-Type',
	                    'application/xml; charset=' . $this->_charset,
	                    true
	                );
	                $response = new Xml($data);
					break;
				case Request::FORMAT_JSON:
					$this->setHeader(
	                    'Content-Type',
	                    'application/json; charset=' . $this->_charset,
	                    true
	                );
	                $response = new Json($data);
					break;
				case Request::FORMAT_AMF:
					$this->setHeader(
	                    'Content-Type',
	                    'application/x-amf; charset=' . $this->_charset,
	                    true
	                );
					$response = new Amf($data);
					break;
			}
            
			return $response;
        }

        /**
         * Can we send headers?
         *
         * @param boolean $throw Whether or not to throw an exception if headers have been sent; defaults to false
         * @return boolean
         * @throws Swift\Exception
         */
        public function canSendHeaders($throw = false)
        {
            $ok = headers_sent($file, $line);

            if ($ok && $throw && $this->headersSentThrowsException) {
                throw new Exception('Cannot send headers; headers already sent in ' . $file . ', line ' . $line);
            }

            return !$ok;
        }

        /**
         * Send all headers
         *
         * Sends any headers specified. If an {@link setHttpResponseCode() HTTP response code}
         * has been specified, it is sent with the first header.
         *
         * @return Swift\Rest\Response
         */
        public function sendHeaders()
        {
            // Only check if we can send headers if we have headers to send
            if (count($this->_headers) || (200 != $this->_httpResponseCode)) {
                $this->canSendHeaders(true);
            }

            $httpCodeSent = false;

            foreach ($this->_headers as $header) {
                if (!$httpCodeSent && $this->_httpResponseCode) {
                    header(
						$header['name'] . ': ' . $header['value'], 
						$header['replace'], 
						$this->_httpResponseCode
					);
                    $httpCodeSent = true;
                } else {
                    header($header['name'] . ': ' . $header['value'], $header['replace']);
                }
            }

            if (!$httpCodeSent) {
                header(
					'HTTP/1.1 ' . $this->_httpResponseCode . ' ' . self::$httpResponses[$this->_httpResponseCode], 
					true, 
					$this->_httpResponseCode
				);
                $httpCodeSent = true;
            }

            return $this;
        }

        /**
         * Send the response, including all headers, rendering exceptions if so
         * requested.
         *
         * @return void
         */
        public function sendResponse()
        {
            $output = $this->render($this->_data);
            $this->sendHeaders();
            echo (string)$output;
        }

        /**
         * Get to string response
         *
         * @return string
         */
        public function __toString()
        {
            ob_start();
            $this->sendResponse();
            return ob_get_clean();
        }
    }
}