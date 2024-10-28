<?php

namespace LaravelDoctrineTest\ORM\Assets\Testing;

class ChildHydrateableClass extends AncestorHydrateableClass
{
    private string $description = '';

    public function getDescription(): string
    {
        return $this->description;
    }
}
