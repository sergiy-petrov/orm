<?php

namespace LaravelDoctrineTest\ORM\Assets\Configuration;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class TypeMock extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return '';
    }

    public function getName()
    {
    }
}
