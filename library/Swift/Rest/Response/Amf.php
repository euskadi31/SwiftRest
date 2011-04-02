<?php
/**
 * @package		Swift
 * @author		Axel ETCHEVERRY <axel@etcheverry.biz>
 * @copyright   Copyright (c) 2011 Axel ETCHEVERRY (http://www.axel-etcheverry.com)
 * Displays		<a href="http://creativecommons.org/licenses/MIT/deed.fr">MIT</a>
 * @license		http://creativecommons.org/licenses/MIT/deed.fr	MIT
 * @version     $Id: Json.php,v1.0 31 mars 2011 03:20:22 euskadi31 $;
 */

namespace Swift\Rest\Response
{
	require_once 'Zend/Amf/Parse/OutputStream.php';
	require_once 'Zend/Amf/Parse/Amf3/Serializer.php';
	
    class Amf
    {
        /**
         * @var string
         */
        protected $_xml;

        /**
         * @var array
         */
        protected $_data;

        /**
         * Init amf response
		 *
         * @param array $data
         */
        public function __construct(array $data)
        {
            $this->_data = (array)$data;
        }

        /**
         * @return string
         */
        public function __toString()
        {
			$stream = new Zend_Amf_Parse_OutputStream();
			$serializer = new Zend_Amf_Parse_Amf3_Serializer($stream);
			$serializer->writeTypeMarker($this->_data);
			return (string)$stream->getStream();
        }
    }
}