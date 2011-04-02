<?php
/**
 * @package     Swift
 * @author      Axel ETCHEVERRY <axel@etcheverry.biz>
 * @copyright   Copyright (c) 2011 Axel ETCHEVERRY (http://www.axel-etcheverry.com)
 * Displays     <a href="http://creativecommons.org/licenses/MIT/deed.fr">MIT</a>
 * @license     http://creativecommons.org/licenses/MIT/deed.fr    MIT
 */

namespace Swift\Rest\Response
{
    class Xml
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
         * Init xml response
         *
         * @param array $data
         */
        public function __construct(array $data)
        {
            $this->_data = (array)$data;
        }

        /**
         * recurcif method
         *
         * @param array $array
         * @return void
         */
        public function _xml($array)
        {
            foreach($array as $k => $v) {
                if(is_array($v)) {
                    $tag = preg_replace('/^[0-9]{1,}/', 'data', $k); // replace numeric key in array to 'data'
                    $tag = $this->_tag($tag);

                    $this->_xml .= "<{$tag}>" . PHP_EOL;
                    $this->_xml($v);
                    $this->_xml .= "</{$tag}>" . PHP_EOL;
                } elseif(is_object($v)) {
                    $tag = preg_replace('/^[0-9]{1,}/', 'data', $k); // replace numeric key in array to 'data'
                    $tag = $this->_tag($tag);

                    $this->_xml .= "<{$tag}>" . PHP_EOL;
                    $this->_xml((array)$v);
                    $this->_xml .= "</{$tag}>" . PHP_EOL;
                } else {
                    $tag = preg_replace('/^[0-9]{1,}/', 'data', $k); // replace numeric key in array to 'data'
                    $tag = $this->_tag($tag);

                    $this->_xml .= "<{$tag}>" . htmlspecialchars($v, ENT_QUOTES, 'UTF-8', true) . "</{$tag}>" . PHP_EOL;
                }
            }
        }

        /**
         * format tag name
         *
         * @param string $tag
         * @return $string
         */
        protected function _tag($tag)
        {
            $search = array(
                "À","Á","Â","Ã","Ä","Å","à","á","â",
                "ã","ä","å","Ò","Ó","Ô","Õ","Ö","Ø",
                "ò","ó","ô","õ","ö","ø","È","É","Ê",
                "Ë","è","é","ê","ë","Ç","ç","Ì","Í",
                "Î","Ï","ì","í","î","ï","Ù","Ú","Û",
                "Ü","ù","ú","û","ü","ÿ","Ñ","ñ"
            );

            $replace = array(
                "A","A","A","A","A","A","a","a","a",
                "a","a","a","O","O","O","O","O","O",
                "o","o","o","o","o","o","E","E","E",
                "E","e","e","e","e","C","c","I","I",
                "I","I","i","i","i","i","U","U","U",
                "U","u","u","u","u","y","N","n"
            );

            $tag = str_replace($search, $replace, $tag);

            $tag = mb_strtolower($tag);
            $tag = preg_replace('/\s/', '_', $tag);
            $tag = preg_replace('/_{2,}/', '_', $tag);

            return $tag;
        }

        /**
         * @return string
         */
        public function __toString()
        {
            $this->_xml($this->_data);
            $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" . PHP_EOL;
            $xml .= '<responses>' . PHP_EOL;
            $xml .= $this->_xml;
            $xml .= '</responses>' . PHP_EOL;

            return (string)$xml;
        }
    }
}