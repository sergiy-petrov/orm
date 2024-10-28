<?php

declare(strict_types=1);

namespace LaravelDoctrineTest\ORM\Feature\Configuration\Cache;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Foundation\Application;
use LaravelDoctrine\ORM\Configuration\Cache\ArrayCacheProvider;
use LaravelDoctrine\ORM\Configuration\Cache\CacheManager;
use LaravelDoctrine\ORM\Configuration\Cache\FileCacheProvider;
use LaravelDoctrine\ORM\Exceptions\DriverNotFound;
use LaravelDoctrineTest\ORM\TestCase;
use Mockery as m;
use Symfony\Component\Cache\Adapter\ArrayAdapter;

class CacheManagerTest extends TestCase
{
    protected CacheManager $manager;

    protected Container $app;

    protected Repository $config;

    protected function setUp(): void
    {
        $this->app = m::mock(Container::class);
        $this->app->shouldReceive('make')->andReturn(m::self());
        $this->app->shouldReceive('get')->with('doctrine.cache.default', 'array')->andReturn('array');

        $this->manager = new CacheManager(
            $this->app,
        );

        parent::setUp();
    }

    public function testDriverReturnsTheDefaultDriver(): void
    {
        $this->app->shouldReceive('resolve')->andReturn(new ArrayCacheProvider());

        $this->assertInstanceOf(ArrayCacheProvider::class, $this->manager->driver());
        $this->assertInstanceOf(ArrayAdapter::class, $this->manager->driver()->resolve());
    }

    public function testDriverCanReturnAGivenDriver(): void
    {
        $config = m::mock(Repository::class);
        $app    = m::mock(Application::class);

        $this->app->shouldReceive('resolve')->andReturn(new FileCacheProvider(
            $config,
            $app,
        ));

        $this->assertInstanceOf(FileCacheProvider::class, $this->manager->driver());
    }

    public function testCantResolveUnsupportedDrivers(): void
    {
        $this->expectException(DriverNotFound::class);
        $this->manager->driver('non-existing');
    }

    public function testCanCreateCustomDrivers(): void
    {
        $this->manager->extend('new', static function () {
            return 'provider';
        });

        $this->assertEquals('provider', $this->manager->driver('new'));
    }

    public function testCanUseApplicationWhenExtending(): void
    {
        $this->manager->extend('new', function ($app): void {
            $this->assertInstanceOf(Container::class, $app);
        });

        $this->assertTrue(true);
    }

    public function testCanReplaceAnExistingDriver(): void
    {
        $this->manager->extend('memcache', static function () {
            return 'provider';
        });

        $this->assertEquals('provider', $this->manager->driver('memcache'));
    }

    protected function tearDown(): void
    {
        m::close();

        parent::tearDown();
    }
}
