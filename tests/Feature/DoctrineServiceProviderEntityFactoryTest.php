<?php

declare(strict_types=1);

namespace LaravelDoctrineTest\ORM\Feature;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use LaravelDoctrineTest\ORM\TestCase;
use Mockery as m;
use Workbench\App\Entities\User;

use function entity;

class DoctrineServiceProviderEntityFactoryTest extends TestCase
{
    public function testEntityFactory(): void
    {
        $cmMock = m::mock(ClassMetadata::class);
        $cmMock->expects('getAssociationMappings')->twice()->andReturn([]);

        $emMock = m::mock(EntityManagerInterface::class);
        $config = new Configuration();

        $config->setProxyDir('tmp');
        $config->setProxyNamespace('');

        $config->setAutoGenerateProxyClasses(true);
        $emMock->expects('getConfiguration')->twice()->andReturn($config);
        $emMock->expects('getClassMetadata')->twice()->andReturn($cmMock);
        $mrMock = m::mock(ManagerRegistry::class);
        $mrMock->expects('getManagers')->andReturn([$emMock]);
        $mrMock->expects('getManagerForClass')->twice()->andReturn($emMock);

        $this->app->bind('registry', static fn () => $mrMock);

        $user = entity(User::class)->make(['password' => 'abc']);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('abc', $user->password);

        $user = entity(User::class, 'test')->make();
        $this->assertEquals('test', $user->name);
    }
}
