<?php

namespace LaravelDoctrineTest\ORM\Assets\Extensions;

use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManagerInterface;
use LaravelDoctrine\ORM\Extensions\Extension;
use LaravelDoctrineTest\ORM\Feature\Extensions\ExtensionManagerTest;

class ExtensionMock implements Extension
{
    public function addSubscribers(EventManager $manager, EntityManagerInterface $em): void
    {
        // Confirm it gets called
        (new ExtensionManagerTest())->assertTrue(true);
    }

    /**
     * @return mixed[]
     */
    public function getFilters(): array
    {
        return [];
    }
}
