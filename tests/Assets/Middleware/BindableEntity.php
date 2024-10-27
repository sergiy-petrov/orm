<?php

namespace LaravelDoctrineTest\ORM\Assets\Middleware;

class BindableEntity
{
    public $id;

    public $name;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return strtolower($this->name);
    }
}
