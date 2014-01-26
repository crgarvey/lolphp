<?php
/**
 * Created for Lolphp on 1/24/14.
 *
 * @author Robbie Vaughn <robbie@robbievaughn.me>
 */

use Zend\Config\Config as ZendConfig;
use Zend\Di\Di as ZendDi;
use Phalcon\Cache\Frontend\Data as PhalconFrontendCache;
use Phalcon\Cache\Backend\File as PhalconCache;
use Lolphp\Connection;
use Lolphp\Configuration;
use Lolphp\RepositoryFactory;
use Lolphp\EntityManager;

define('APPLICATION_PATH', realpath(__DIR__));

require APPLICATION_PATH . '/../vendor/autoload.php';

/**
 * Dependency Injection Container
 */
$di                                     = new ZendDi();

/**
 * Configuration
 *
 * Instantiate Dependencies into Dependency Injection.
 */

// File Configuration
$di->instanceManager()->addSharedInstance(
    new ZendConfig(include APPLICATION_PATH . '/config/config.php'),
    'config'
);

// Repository Factory
$di->instanceManager()->addSharedInstance(
    new RepositoryFactory(),
    'repositoryFactory'
);

// Cache
$cache = new PhalconCache(new PhalconFrontendCache([
    "lifetime" => 172800
]), [
    "cacheDir" => APPLICATION_PATH . '/tmp/'
]);

$di->instanceManager()->addSharedInstance($cache, 'cache');

/**
 * Instantiate Application Configuration
 */
$applicationConfiguration               = new Configuration($di);
$connection         = new Connection(
    $di->get('config')->api->url,
    $di->get('config')->api->key
);

$em                                     = new EntityManager($connection, $applicationConfiguration);

/**
 * @var \Lolphp\Entity\Summoner $summoner
 */
$summonerList = $em->getRepository(new \Lolphp\Entity\Summoner())->findBy(
    ['summonerIds'  => ['43099783', '37533618']],
    ['id']
);

var_dump($summonerList);

echo "<br> --- <br>";

$summonerList = $em->getRepository(new \Lolphp\Entity\Summoner())->find(43099783);
var_dump($summonerList);
