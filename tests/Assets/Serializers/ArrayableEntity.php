<?php

namespace LaravelDoctrineTest\ORM\Assets\Serializers;

use LaravelDoctrine\ORM\Serializers\Arrayable;

class ArrayableEntity
{
    use Arrayable;

    protected $id = 'IDVALUE';

    protected $name = 'NAMEVALUE';

    protected $list = ['item1', 'item2'];

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getList()
    {
        return $this->list;
    }
}
