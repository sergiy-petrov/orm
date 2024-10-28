<?php

namespace LaravelDoctrineTest\ORM\Feature\Configuration\Cache;

use LaravelDoctrineTest\ORM\TestCase;
use Mockery;

abstract class AbstractCacheProviderTest extends TestCase
{
    abstract public function getProvider();

    abstract public function getExpectedInstance();

    public function test_can_resolve()
    {
        $this->assertInstanceOf($this->getExpectedInstance(), $this->getProvider()->resolve());
    }

    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
