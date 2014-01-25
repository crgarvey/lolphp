<?php
/**
 * Created for Lolphp on 1/25/14.
 *
 * @author Robbie Vaughn <robbie@robbievaughn.me>
 */
namespace Lolphp\Repository;

use Lolphp\Entity\EntityInterface;
use Lolphp\EntityManagerInterface;

abstract class RepositoryAbstract implements RepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    protected $entityName;

    /**
     * @param EntityManagerInterface $entityManager
     * @param string                 $entityName
     */
    public function __construct(EntityManagerInterface $entityManager, $entityName)
    {
        $this->entityManager = $entityManager;
        $this->entityName    = $entityName;
    }

    /**
     * @param $id
     *
     * @return EntityInterface
     */
    public function find($id)
    {
        return array();
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->findBy(array());
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     * @param null  $limit
     * @param null  $offset
     *
     * @return EntityInterface|array()
     */
    public function findBy(Array $criteria, Array $orderBy = null, $limit = null, $offset = null)
    {
        return array();
    }

    /**
     * @param array $criteria
     * @param array $orderBy
     *
     * @return EntityInterface
     */
    public function findOneBy(Array $criteria, Array $orderBy = null)
    {
        return null;
    }

    /**
     * @param $list
     * @param $orderBy
     * @param $direction
     *
     * @return mixed
     */
    protected function orderBy($list, $orderBy, $direction = 1)
    {
        usort($list, function($a, $b) use ($orderBy, $direction) {
            $aVal = $a->{'get' . ucwords($orderBy)}();
            $bVal = $b->{'get' . ucwords($orderBy)}();

            return ($aVal == $bVal ? 0 : ($aVal > $bVal ? 1 * $direction : -1 * $direction));
        });

        return $list;
    }
}