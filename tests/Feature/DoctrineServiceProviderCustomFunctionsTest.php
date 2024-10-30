<?php

declare(strict_types=1);

namespace LaravelDoctrineTest\ORM\Feature;

use Doctrine\Persistence\ManagerRegistry;
use DoctrineExtensions\Query\Mysql\Ascii;
use DoctrineExtensions\Query\Mysql\Cos;
use DoctrineExtensions\Query\Mysql\Date;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use LaravelDoctrineTest\ORM\TestCase;

use function tap;

class DoctrineServiceProviderCustomFunctionsTest extends TestCase
{
    /**
     * @param Application $app
     * phpcs:disable
     */
    protected function defineEnvironment($app): void
    {
        // Setup default database to use sqlite :memory:
        tap($app['config'], static function (Repository $config): void {
            $config->set('doctrine.custom_datetime_functions', [
                'DATE' => Date::class,
            ]);
            $config->set('doctrine.custom_numeric_functions', [
                'COS' => Cos::class,
            ]);
            $config->set('doctrine.custom_string_functions', [
                'ASCII' => Ascii::class,
            ]);
        });
    }

    public function testRegistryIsRegistered(): void
    {
        $registry = $this->app->get('registry');

        $this->assertInstanceOf(
            ManagerRegistry::class,
            $registry,
        );
    }
}
