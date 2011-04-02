<?php
/**
 * @package Swift
 * @copyright Copyright 2011 Axel ETCHEVERRY (http://www.axel-etcheverry.com)
 * @license GPL
 * @version $Id: bootstrap.php,v1.0 28 mars 2011 11:54:55 euskadi31 $;
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
        '/forums/:forum_id/threads/:thread_id/posts/:port_id' => 'Controller\\Forums\\Posts'
    ));

    $server->handle();
} catch (Exception $e) {
    die(new Error($e));
}