<?php

namespace App\Entity;

abstract class Car
{
    protected $id;
    protected $name;
    protected $year;

    public function getId(): ?int
    {
        return $this->id;
    }
}
