<?php

namespace LaravelDoctrineTest\ORM\Assets\Middleware;

use LaravelDoctrine\ORM\Contracts\UrlRoutable;

class BindableEntityWithInterface implements UrlRoutable
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

    public static function getRouteKeyNameStatic(): string
    {
        return 'name';
    }
}
