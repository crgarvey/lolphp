<?php
/**
 * Created for Lolphp on 1/24/14.
 *
 * @author Robbie Vaughn <robbie@robbievaughn.me>
 */
namespace Lolphp;

use Lolphp\Entity\EntityInterface;

interface RepositoryFactoryInterface
{
    /**
     * @param EntityManagerInterface $em
     * @param EntityInterface        $entity
     *
     * @return mixed
     */
    public function getRepository(EntityManagerInterface $em, EntityInterface $entity);
}
