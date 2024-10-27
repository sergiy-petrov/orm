<?php

namespace LaravelDoctrineTest\ORM\Assets\Testing;

class ChildHydrateableClass extends AncestorHydrateableClass
{
    private $description;

    public function getDescription()
    {
        return $this->description;
    }
}
