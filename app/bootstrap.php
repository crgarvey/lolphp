<?php
/**
 * Created for Lolphp on 1/24/14.
 *
 * @author Robbie Vaughn <robbie@robbievaughn.me>
 */

use Zend\Config\Config as ZendConfig;
use Zend\Di\Di as ZendDi;
use Lolphp\Connection;

define('APPLICATION_PATH', realpath(__DIR__));

require APPLICATION_PATH . '/../vendor/autoload.php';

/**
 * Dependency Injection Container
 */

// Instantiate Dependency Injection.
$di                 = new ZendDi();

/**
 * Configuration
 */

// Instantiate Configuration into Dependency Injection.
$di->instanceManager()->addAlias(
    'config',
    get_class(new ZendConfig([])),
    ['array' => include APPLICATION_PATH . '/config/config.php']
);

$connection         = new Connection(
    $config->api->url,
    $config->api->key,
    Connection::APIMETHOD_SUMMONER,
    Connection::SUMMONER_VERSION
);

// Example response for summoner 'jellybao' as a search.
$response           = $connection->call('by-name/jellybao', 'na');
var_dump($response);