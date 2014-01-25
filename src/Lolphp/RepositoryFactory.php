<?php
/**
 * Created for Lolphp on 1/25/14.
 *
 * @author Robbie Vaughn <robbie@robbievaughn.me>
 */
namespace Lolphp;

use Lolphp\Entity\EntityInterface;

/**
 * Class RepositoryFactory
 * @package Lolphp
 */
class RepositoryFactory implements RepositoryFactoryInterface
{
    private $repositoryList = array();

    /**
     * @param EntityManagerInterface $entityManager
     * @param EntityInterface        $entity
     *
     * @return RepositoryInterface|mixed
     */
    public function getRepository(EntityManagerInterface $entityManager, EntityInterface $entity)
    {
        $entityName = ltrim(get_class($entity), '\\');

        if (!empty($this->repositoryList[$entityName])) {
            return $this->repositoryList[$entityName];
        }

        $repository = $this->createRepository($entityManager, $entityName);
        $this->repositoryList[$entityName] = $repository;

        return $repository;
    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param string                 $entityName
     *
     * @return RepositoryInterface
     */
    public function createRepository(EntityManagerInterface $entityManager, $entityName)
    {
        $repositoryName = str_replace('\\Entity\\', '\\Repository\\', $entityName);

        if (!class_exists($repositoryName)) {
            throw new \Exception('Invalid Repository For ' . $entityName);
        }

        return new $repositoryName($entityManager, $entityName);
    }

}