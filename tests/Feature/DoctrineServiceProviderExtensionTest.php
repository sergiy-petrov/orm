<?php

declare(strict_types=1);

namespace LaravelDoctrineTest\ORM\Feature;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use LaravelDoctrine\Extensions\Blameable\BlameableExtension;
use LaravelDoctrine\Extensions\IpTraceable\IpTraceableExtension;
use LaravelDoctrine\Extensions\Loggable\LoggableExtension;
use LaravelDoctrine\Extensions\Sluggable\SluggableExtension;
use LaravelDoctrine\Extensions\SoftDeletes\SoftDeleteableExtension;
use LaravelDoctrine\Extensions\Sortable\SortableExtension;
use LaravelDoctrine\Extensions\Timestamps\TimestampableExtension;
use LaravelDoctrine\Extensions\Translatable\TranslatableExtension;
use LaravelDoctrine\Extensions\Tree\TreeExtension;
use LaravelDoctrineTest\ORM\TestCase;

use function tap;

class DoctrineServiceProviderExtensionTest extends TestCase
{
    /**
     * @param Application $app
     * phpcs:disable
     */
    protected function defineEnvironment($app): void
    {
        // Setup default database to use sqlite :memory:
        tap($app['config'], static function (Repository $config): void {
            $config->set('doctrine.extensions', [
                TimestampableExtension::class,
                SoftDeleteableExtension::class,
                SluggableExtension::class,
                SortableExtension::class,
                TreeExtension::class,
                LoggableExtension::class,
                BlameableExtension::class,
                IpTraceableExtension::class,
                TranslatableExtension::class,
            ]);
        });
    }

    public function testExtensions(): void
    {
        $registry = $this->app->get('registry');

        $this->assertTrue(true);
    }
}
