<?php
/**
 * @package
 * @copyright   Copyright (c) 2011 Axel ETCHEVERRY (http://www.axel-etcheverry.com)
 * @license
 * @version     $Id: Json.php,v1.0 31 mars 2011 03:20:22 euskadi31 $;
 */

namespace Swift\Rest\Response
{
    class Json
    {
        /**
         * @var array
         */
        protected $_data;

        /**
         * @param array $data
         */
        public function __construct(array $data)
        {
            $this->_data = (array) $data;
        }

        /**
         * @return string
         */
        public function __toString()
        {
            if(class_exists('Zend_Json')) {
                $json = (string)Zend_Json::encode($this->_data);
            } else {
                $json = (string)json_encode($this->_data);
            }

            if(isset($_GET['indent']) && !empty($_GET['indent'])) {
                return $this->_indent($json);
            }

            return $json;
        }

        /**
         * Indent json response
         * @param string $json
         * @return string
         */
        protected function _indent($json)
        {

            $result    = '';
            $pos       = 0;
            $strLen    = strlen($json);
            $indentStr = '  ';
            $newLine   = "\n";

            for($i = 0; $i <= $strLen; $i++) {
                // Grab the next character in the string
                $char = substr($json, $i, 1);

                // If this character is the end of an element,
                // output a new line and indent the next line
                if($char == '}' || $char == ']') {
                    $result .= $newLine;
                    $pos --;
                    for ($j=0; $j<$pos; $j++) {
                        $result .= $indentStr;
                    }
                }

                // Add the character to the result string
                $result .= $char;

                // If the last character was the beginning of an element,
                // output a new line and indent the next line
                if ($char == ',' || $char == '{' || $char == '[') {
                    $result .= $newLine;
                    if ($char == '{' || $char == '[') {
                        $pos ++;
                    }

                    for ($j = 0; $j < $pos; $j++) {
                        $result .= $indentStr;
                    }
                }
            }

            return $result;
        }
    }
}