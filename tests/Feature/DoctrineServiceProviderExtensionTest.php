<?php

declare(strict_types=1);

namespace LaravelDoctrineTest\ORM\Feature;

use Illuminate\Config\Repository;
use LaravelDoctrineTest\ORM\TestCase;

class DoctrineServiceProviderExtensionTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        // Setup default database to use sqlite :memory:
        tap($app['config'], function (Repository $config) {
            $config->set('doctrine.extensions', [
                \LaravelDoctrine\Extensions\Timestamps\TimestampableExtension::class,
                \LaravelDoctrine\Extensions\SoftDeletes\SoftDeleteableExtension::class,
                \LaravelDoctrine\Extensions\Sluggable\SluggableExtension::class,
                \LaravelDoctrine\Extensions\Sortable\SortableExtension::class,
                \LaravelDoctrine\Extensions\Tree\TreeExtension::class,
                \LaravelDoctrine\Extensions\Loggable\LoggableExtension::class,
                \LaravelDoctrine\Extensions\Blameable\BlameableExtension::class,
                \LaravelDoctrine\Extensions\IpTraceable\IpTraceableExtension::class,
                \LaravelDoctrine\Extensions\Translatable\TranslatableExtension::class
            ]);
        });
    }

    public function testExtensions(): void
    {
        $registry = $this->app->get('registry');

        $this->assertTrue(true);
    }
}
