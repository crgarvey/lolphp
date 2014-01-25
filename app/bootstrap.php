<?php
/**
 * Created for Lolphp on 1/24/14.
 *
 * @author Robbie Vaughn <robbie@robbievaughn.me>
 */

use Zend\Config\Config as ZendConfig;
use Lolphp\Connection;

define('APPLICATION_PATH', realpath(__DIR__));

require APPLICATION_PATH . '/../vendor/autoload.php';

/**
 * Configuration
 */

// Instantiate an instance of ZendConfig and retrieve config.
$config             = new ZendConfig(include APPLICATION_PATH . '/config/config.php');

$connection         = new Connection(
    $config->api->url,
    $config->api->key,
    Connection::APIMETHOD_SUMMONER,
    Connection::SUMMONER_VERSION
);

// Example response for summoner 'jellybao' as a search.
$response           = $connection->call('by-name/jellybao');
var_dump($response);