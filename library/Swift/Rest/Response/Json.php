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
                return $this->_prettyPrint($json);
            }

            return $json;
        }

		/**
		 * Pretty print some JSON
		 * 
		 * @param string $json
	     * @return string
		 */
		protected function _prettyPrint($json)
		{
			$tab = "  ";
			$new_json = "";
			$indent_level = 0;
			$in_string = false;

			$len = strlen($json);

			for($c = 0; $c < $len; $c++) {
				$char = $json[$c];
				switch($char) {
					case '{':
					case '[':
						if(!$in_string) {
							$new_json .= $char . "\n" . str_repeat($tab, $indent_level+1);
							$indent_level++;
						} else {
							$new_json .= $char;
						}
						break;
					case '}':
					case ']':
						if(!$in_string) {
							$indent_level--;
							$new_json .= "\n" . str_repeat($tab, $indent_level) . $char;
						} else {
							$new_json .= $char;
						}
						break;
					case ',':
						if(!$in_string) {
							$new_json .= ",\n" . str_repeat($tab, $indent_level);
						} else {
							$new_json .= $char;
						}
						break;
					case ':':
						if(!$in_string) {
							$new_json .= ": ";
						} else {
							$new_json .= $char;
						}
						break;
					case '"':
						if($c > 0 && $json[$c-1] != '\\') {
							$in_string = !$in_string;
						}
					default:
						$new_json .= $char;
						break;					
				}
			}

			return $new_json;
		}
    }
}