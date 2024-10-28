<?php

namespace LaravelDoctrineTest\ORM\Feature\Configuration\MetaData;

use Doctrine\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\Persistence\Mapping\Driver\PHPDriver;
use LaravelDoctrine\ORM\Configuration\MetaData\Php;
use LaravelDoctrineTest\ORM\TestCase;
use Mockery as m;

class PhpTest extends TestCase
{
    /**
     * @var Php
     */
    protected $meta;

    protected function setUp(): void
    {
        $this->meta = new Php();

        parent::setUp();
    }

    public function test_can_resolve()
    {
        $resolved = $this->meta->resolve([
            'paths'   => ['entities'],
            'dev'     => true,
            'proxies' => ['path' => 'path']
        ]);

        $this->assertInstanceOf(MappingDriver::class, $resolved);
        $this->assertInstanceOf(PHPDriver::class, $resolved);
    }

    protected function tearDown(): void
    {
        m::close();
    }
}
