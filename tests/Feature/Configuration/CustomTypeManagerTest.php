<?php

namespace LaravelDoctrineTest\ORM\Feature\Configuration;

use Doctrine\DBAL\Exception as DBALException;
use LaravelDoctrine\ORM\Configuration\CustomTypeManager;
use LaravelDoctrineTest\ORM\Assets\Configuration\TypeMock;
use LaravelDoctrineTest\ORM\Assets\Configuration\TypeMock2;
use LaravelDoctrineTest\ORM\TestCase;

class CustomTypeManagerTest extends TestCase
{
    public function test_can_add_type()
    {
        $manager = new CustomTypeManager;

        $manager->addType('type', TypeMock::class);

        $this->assertInstanceOf(TypeMock::class, $manager->getType('type'));
    }

    public function test_can_overwrite_type()
    {
        $manager = new CustomTypeManager;

        $manager->addType('type2', TypeMock::class);
        $this->assertInstanceOf(TypeMock::class, $manager->getType('type2'));

        $manager->addType('type2', TypeMock2::class);
        $this->assertInstanceOf(TypeMock2::class, $manager->getType('type2'));
    }

    public function test_can_add_multiple_types()
    {
        $manager = new CustomTypeManager;

        $manager->addCustomTypes([
            'type3' => TypeMock::class,
            'type4' => TypeMock2::class
        ]);

        $this->assertInstanceOf(TypeMock::class, $manager->getType('type3'));
        $this->assertInstanceOf(TypeMock2::class, $manager->getType('type4'));
    }

    public function test_cannot_get_non_existing_type()
    {
        $this->expectException(DBALException::class);

        $manager = new CustomTypeManager;
        $manager->getType('non_existing');
    }
}
