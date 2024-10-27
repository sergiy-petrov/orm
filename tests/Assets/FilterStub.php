<?php

namespace LaravelDoctrineTest\ORM\Assets;

class FilterStub extends \Doctrine\ORM\Query\Filter\SQLFilter
{
    public function addFilterConstraint(\Doctrine\ORM\Mapping\ClassMetadata $targetEntity, string $targetTableAlias): string
    {
        return '';
    }
}
