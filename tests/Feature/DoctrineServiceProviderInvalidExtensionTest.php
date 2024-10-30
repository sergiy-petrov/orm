<?php

declare(strict_types=1);

namespace LaravelDoctrineTest\ORM\Feature;

use Illuminate\Config\Repository;
use LaravelDoctrine\ORM\Exceptions\ExtensionNotFound;
use LaravelDoctrineTest\ORM\TestCase;

class DoctrineServiceProviderInvalidExtensionTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        // Setup default database to use sqlite :memory:
        tap($app['config'], function (Repository $config) {
            $config->set('doctrine.extensions', [
                'invalid' => 'InvalalidExtension',
            ]);
        });
    }

    public function testInvalidException(): void
    {
        $this->expectException(ExtensionNotFound::class);

        $registry = $this->app->get('registry');
    }
}
