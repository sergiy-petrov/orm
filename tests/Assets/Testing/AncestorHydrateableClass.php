<?php

namespace LaravelDoctrineTest\ORM\Assets\Testing;

class AncestorHydrateableClass
{
    private string $name = '';

    public function getName(): string
    {
        return $this->name;
    }
}
