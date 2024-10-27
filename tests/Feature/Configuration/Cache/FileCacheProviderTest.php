<?php

namespace LaravelDoctrineTest\ORM\Feature\Configuration\Cache;

use Illuminate\Contracts\Config\Repository;
use LaravelDoctrine\ORM\Configuration\Cache\FileCacheProvider;
use Mockery as m;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class FileCacheProviderTest extends AbstractCacheProviderTest
{
    public function getProvider()
    {
        $config = m::mock(Repository::class);
        $config->shouldReceive('get')
            ->with('cache.stores.file.path', 'framework/cache')
            ->once()
            ->andReturn('/tmp');

        $config->shouldReceive('get')
            ->with('doctrine.cache.namespace', 'doctrine-cache')
            ->once()
            ->andReturn('doctrine-cache');

        return new FileCacheProvider(
            $config,
        );
    }

    public function getExpectedInstance()
    {
        return FilesystemAdapter::class;
    }
}

