<?php
/**
 * @package     Swift
 * @author      Axel ETCHEVERRY <axel@etcheverry.biz>
 * @copyright   Copyright (c) 2011 Axel ETCHEVERRY (http://www.axel-etcheverry.com)
 * Displays     <a href="http://creativecommons.org/licenses/MIT/deed.fr">MIT</a>
 * @license     http://creativecommons.org/licenses/MIT/deed.fr    MIT
 */

require_once __DIR__ . '/../library/Swift/Loader/ClassLoader.php';

use Swift\Loader\ClassLoader;

$loader = new ClassLoader();
$loader->registerNamespaces(array(
    'Controller'    => __DIR__,
    'Swift'         => __DIR__ . '/../library',
));
$loader->register();

use Swift\Rest\Server,
    Swift\Rest\Error,
    Swift\Exception;

try {
    $server = new Server(array(
        '/news/:news_id' => 'Controller\\News',
        '/news/:news_id/comments/:comment_id' => 'Controller\\NewsComments',
        '/events/:event_id' => 'Controller\\Events',
        '/classified/cars/:car_id' => 'Controller\\ClassifiedsCars',
        '/classified/parts/:part_id' => 'Controller\\ClassifiedsParts',
        '/forums/:forum_id' => 'Controller\\Forums',
        '/forums/:forum_id/threads/:thread_id' => 'Controller\\ForumsThreads',
        '/forums/:forum_id/threads/:thread_id/posts/:post_id' => 'Controller\\ForumsPosts'
    ));

    $server->handle();
} catch (Exception $e) {
    die(new Error($e));
}