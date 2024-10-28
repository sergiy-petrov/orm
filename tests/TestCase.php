<?php

declare(strict_types=1);

namespace LaravelDoctrineTest\ORM;

use Illuminate\Foundation\Application;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    private Application $application;

    protected function setUp(): void
    {
        $this->application = new Application();

        parent::setUp();
    }

    protected function tearDown(): void
    {
        unset($this->application);

        parent::tearDown();
    }

    public function getApplication(): Application
    {
        return $this->application;
    }
}
