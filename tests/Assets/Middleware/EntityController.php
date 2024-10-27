<?php

namespace LaravelDoctrineTest\ORM\Assets\Middleware;

use Illuminate\Http\Request;

class EntityController
{
    public function index(BindableEntity $entity)
    {
        return $entity->getName();
    }

    public function interfacer(BindableEntityWithInterface $entity)
    {
        return $entity->getId();
    }

    public function returnValue(string $value)
    {
        return $value;
    }

    public function returnEntity(BindableEntity $entity = null) {
        return $entity;
    }

    public function returnEntityName(BindableEntity $entity) {
        return $entity->getName();
    }

    public function checkRequest(Request $request) {
        return $request instanceof Request ? 'request' : 'something else';
    }
}
