<?php
/**
 * @package     Swift
 * @copyright   Copyright (c) 2011 Axel ETCHEVERRY (http://www.axel-etcheverry.com)
 * @license     GPL
 * @version     $Id: News.php,v1.0 29 mars 2011 01:12:09 euskadi31 $;
 */

namespace Controller
{
    use Swift\Rest\Controller;

    class News extends Controller
    {

        /**
         * Initialize object
         *
         * Called from {@link __construct()} as final step of object instantiation.
         *
         * @return void
         */
        public function init()
        {

        }

        /**
         * Pre-dispatch routines
         *
         * Called before get,post,put and delete method.
         *
         * @return void
         */
        public function preDispatch()
        {

        }

        /**
         * Get action
         *
         * @return array
         */
        public function get()
        {
            return array('name' => 'Axel');
        }

        /**
         * Post action
         *
         * @return array
         */
        public function post()
        {

        }

        /**
         * Put action
         *
         * @return array
         */
        public function put()
        {

        }

        /**
         * Delete action
         *
         * @return array
         */
        public function delete()
        {

        }

        /**
         * Post-dispatch routines
         *
         * Called after get,post,put and delete method execution.
         *
         * @return void
         */
        public function postDispatch()
        {

        }
    }
}