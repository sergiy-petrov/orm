<?php

declare(strict_types=1);

namespace LaravelDoctrineTest\ORM\Assets\Testing;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\InverseJoinColumn;

#[Entity]
class EntityStub
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: "integer")]
    public $id;

    #[Column(type: "string")]
    public $name;

    #[ManyToMany(targetEntity: "EntityStub")]
    #[JoinTable(name: "stub_stubs")]
    #[JoinColumn(name: "owner_id", referencedColumnName: "id")]
    #[InverseJoinColumn(name: "owned_id", referencedColumnName: "id")]
    public $others;
}
