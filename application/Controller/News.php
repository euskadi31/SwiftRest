<?php
/**
 * @package     Swift
 * @author      Axel ETCHEVERRY <axel@etcheverry.biz>
 * @copyright   Copyright (c) 2011 Axel ETCHEVERRY (http://www.axel-etcheverry.com)
 * Displays     <a href="http://creativecommons.org/licenses/MIT/deed.fr">MIT</a>
 * @license     http://creativecommons.org/licenses/MIT/deed.fr    MIT
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
            $this->getResponse()->setHttpResponseCode(201);
            return array('post' => $this->_getAllParams());
        }

        /**
         * Put action
         *
         * @return array
         */
        public function put()
        {
            return array('put' => $this->_getAllParams());
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