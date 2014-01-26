<?php
/**
 * Created for Lolphp on 1/24/14.
 *
 * @author Robbie Vaughn <robbie@robbievaughn.me>
 */

// Zend 2
use Zend\Config\Config as ZendConfig;
use Zend\Di\Di as ZendDi;
// Phalcon
use Phalcon\Cache\Frontend\Data as PhalconFrontendCache;
use Phalcon\Cache\Backend\File as PhalconCache;
use Phalcon\DI\FactoryDefault as PhalconDi;
use Phalcon\Loader as PhalconLoader;
use Phalcon\Mvc\Router as PhalconRouter;
use Phalcon\Mvc\View as PhalconView;
use Phalcon\Mvc\Application as PhalconApplication;
// Lolphp
use Lolphp\Connection;
use Lolphp\Configuration;
use Lolphp\RepositoryFactory;
use Lolphp\EntityManager;

define('APPLICATION_PATH', realpath(__DIR__));

require APPLICATION_PATH . '/../vendor/autoload.php';

try {
    /**
     * Cache
     */
    $cache = new PhalconCache(new PhalconFrontendCache([
        "lifetime" => 172800
    ]), [
        "cacheDir" => APPLICATION_PATH . '/tmp/'
    ]);

    /**
     * Dependency Injection Container
     */
    $zendDi                                 = new ZendDi;
    $phalconDi                              = new PhalconDi;

    $dependencyList                         = [
        // File Configuration
        [
            'alias'                         => 'config',
            'object'                        => new ZendConfig(include APPLICATION_PATH . '/config/config.php')
        ],
        // Repository Factory
        [
            'alias'                         => 'repositoryFactory',
            'object'                        => new RepositoryFactory()
        ],
        // Cache
        [
            'alias'                         => 'cache',
            'object'                        => $cache
        ]
    ];
    /**
     * Configuration
     *
     * Instantiate Dependencies into Dependency Injection.
     */

    // Zend 2: DI & Phalcon DI.
    foreach ($dependencyList as $d) {
        $alias      = $d['alias'];
        $obj        = $d['object'];

        $zendDi->instanceManager()->addSharedInstance($obj, $alias);
        $phalconDi->set($alias, $obj);
    }

    /**
     * Instantiate Application Configuration
     */
    $applicationConfiguration               = new Configuration($zendDi);
    $connection         = new Connection(
        $zendDi->get('config')->api->url,
        $zendDi->get('config')->api->key
    );

    $em                                     = new EntityManager($connection, $applicationConfiguration);

    /**
     * MVC Framework Support: Phalcon PHP
     */
    if ($zendDi->get('config')->mvc->isEnabled === true) {
        // Phalcon Loader
        $loader                             = new PhalconLoader;
        $loader->registerNamespaces([
            'Lolphp\Controller'             => APPLICATION_PATH . '/lolphp/controller'
        ]);
        $loader->register();

        // Phalcon Router
        $router                             = new PhalconRouter;
        $router->setDefaultNamespace('Lolphp\Controller');

        // Phalcon View
        $view                               = new PhalconView();
        $view->setViewsDir(APPLICATION_PATH . '/lolphp/view/');

        // Inject dependencies
        $phalconDi->set('loader', $loader);
        $phalconDi->set('router', $router);
        $phalconDi->set('view',  $view);
        $phalconDi->set('em', $em);

        $application                        = new PhalconApplication($phalconDi);
        echo $application->handle()->getContent();
    }
} catch (\Exception $e) {
    echo 'ERROR: ' . $e->getMessage();
}
