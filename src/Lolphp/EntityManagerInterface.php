<?php
/**
 * Created for Lolphp on 1/25/14.
 *
 * @author Robbie Vaughn <robbie@robbievaughn.me>
 */
namespace Lolphp;

use Lolphp\Entity\EntityInterface;
use Lolphp\Repository\RepositoryInterface;

/**
 * Interface EntityManagerInterface
 * @package Lolphp
 */
interface EntityManagerInterface
{
    /**
     * @param EntityInterface $entity
     *
     * @return RepositoryInterface
     */
    public function getRepository(EntityInterface $entity);

    /**
     * @return Configuration
     */
    public function getConfiguration();

    /**
     * @return Connection
     */
    public function getConnection();

    /**
     * @param array $request
     * @param string $region
     * @param string $method
     * @param array $fields
     * @param string $verb
     * @return mixed
     */
    public function call(
        Array $request = [],
        $region = Connection::REGION_NORTHAMERICA,
        $method = '',
        Array $fields = [],
        $verb = Connection::VERB_GET
    );

}