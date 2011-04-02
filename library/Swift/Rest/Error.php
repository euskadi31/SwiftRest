<?php
/**
 * @package     Swift
 * @author		Axel ETCHEVERRY <axel@etcheverry.biz>
 * @copyright   Copyright (c) 2011 Axel ETCHEVERRY (http://www.axel-etcheverry.com)
 * Displays		<a href="http://creativecommons.org/licenses/MIT/deed.fr">MIT</a>
 * @license		http://creativecommons.org/licenses/MIT/deed.fr	MIT
 * @version     $Id: Error.php,v1.0 28 mars 2011 12:20:46 euskadi31 $;
 */

namespace Swift\Rest
{
    require_once __DIR__ . '/Response.php';

    class Error extends Response
    {
        /**
         * Init error
         * @param Exception $exception
         * @return Swift\Rest\Error
         */
        public function __construct(\Exception $exception)
        {
            parent::__construct(array(
                'message'   => $exception->getMessage(),
                'code'      => $exception->getCode(),
                'file'      => $exception->getFile(),
                'line'      => $exception->getLine(),
                'trace'     => $exception->getTrace(),
                'previous'  => $exception->getPrevious()
            ));
        }
    }
}