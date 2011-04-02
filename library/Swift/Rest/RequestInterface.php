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
    interface RequestInterface
    {
        /**
         * Get headers
         * @return array
         */
        public function getHeaders();

        /**
         * Get header by name
         * @param string $name
         * @return string
         */
        public function getHeader($name);

        /**
         * Get format output
         * @return string
         */
        public function getFormat();
    }
}