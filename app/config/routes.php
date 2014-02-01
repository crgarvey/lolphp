<?php
/**
 * Created for Lolphp on 1/26/14.
 *
 * @author Robbie Vaughn <robbie@robbievaughn.me>
 */

use Phalcon\Mvc\Router as PhalconRouter;

/**
 * @var PhalconRouter $router
 */
$router->add(
    '/summoner/{action}/{region}/{term}',
    [
        'controller'        => 'summoner',
        'action'            => 1
    ]
);
