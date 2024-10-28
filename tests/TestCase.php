<?php

namespace LaravelDoctrineTest\ORM;

use Illuminate\Foundation\Application;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    private Application $application;

    protected function setUp(): void
    {
        $this->application = new Application();
    }

    protected function tearDown(): void
    {
        unset($this->application);
    }
}
