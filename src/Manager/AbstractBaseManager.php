<?php
declare(strict_types=1);

namespace App\Manager;

use Doctrine\ORM\EntityManager;

abstract class AbstractBaseManager
{
    protected $class;
    protected $entityManager;

    public function __construct($class, EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->class = $class;
    }

    public function create()
    {
        return new $this->class();
    }

    public function save($entity, $andFlush = true)
    {
        $this->entityManager->persist($entity);

        if ($andFlush) {
            $this->entityManager->flush();
        }
    }
}