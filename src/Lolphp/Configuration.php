<?php
/**
 * Created for Lolphp on 1/25/14.
 *
 * @author Robbie Vaughn <robbie@robbievaughn.me>
 */
namespace Lolphp;

use Zend\Di\DependencyInjectionInterface as ZendDiInterface;
use Phalcon\Cache\Backend\File as PhalconCache;

/**
 * Class Configuration
 * @package Lolphp
 */
class Configuration
{
    /**
     * @var ZendDiInterface $di
     */
    private $di;

    public function __construct(
        ZendDiInterface $di
    ) {
        $this->di               = $di;
    }

    /**
     * @return \Lolphp\RepositoryFactoryInterface
     */
    public function getRepositoryFactory()
    {
        return $this->di->get('repositoryFactory');
    }

    /**
     * @return PhalconCache
     */
    public function getCache()
    {
        return $this->di->get('cache');
    }
}