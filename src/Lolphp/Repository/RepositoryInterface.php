<?php
/**
 * Created for Lolphp on 1/25/14.
 *
 * @author Robbie Vaughn <robbie@robbievaughn.me>
 */
namespace Lolphp\Repository;

use Lolphp\Entity\EntityInterface;

interface RepositoryInterface
{
    const DEF_LIMIT  = 5000;
    const DEF_OFFSET = 1;

    /**
     * @param $id
     *
     * @return EntityInterface
     */
    public function find($id);

    /**
     * @return EntityInterface|array()
     */
    public function findAll();

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null  $limit
     * @param null  $offset
     *
     * @return EntityInterface|array()
     */
    public function findBy(Array $criteria, Array $orderBy = null, $limit = null, $offset = null);

    /**
     * @param array $criteria
     * @param array $orderBy
     *
     * @return EntityInterface
     */
    public function findOneBy(Array $criteria, Array $orderBy = null);
}