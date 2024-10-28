<?php

namespace LaravelDoctrineTest\ORM\Feature\Testing;

use LaravelDoctrine\ORM\Testing\SimpleHydrator;
use LaravelDoctrineTest\ORM\Assets\Testing\AncestorHydrateableClass;
use LaravelDoctrineTest\ORM\Assets\Testing\ChildHydrateableClass;
use LaravelDoctrineTest\ORM\TestCase;

class SimpleHydratorTest extends TestCase
{
    public function test_can_hydrate_class()
    {
        $entity = SimpleHydrator::hydrate(AncestorHydrateableClass::class, [
            'name' => 'Patrick',
        ]);

        $this->assertInstanceOf(AncestorHydrateableClass::class, $entity);
        $this->assertEquals('Patrick', $entity->getName());
    }

    public function test_can_hydrate_with_extension_of_private_properties()
    {
        $entity = SimpleHydrator::hydrate(ChildHydrateableClass::class, [
            'name'        => 'Patrick',
            'description' => 'Hello World',
        ]);

        $this->assertInstanceOf(ChildHydrateableClass::class, $entity);
        $this->assertEquals('Patrick', $entity->getName());
        $this->assertEquals('Hello World', $entity->getDescription());
    }
}
