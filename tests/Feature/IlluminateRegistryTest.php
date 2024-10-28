<?php

namespace LaravelDoctrineTest\ORM\Feature;

use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;
use LaravelDoctrine\ORM\EntityManagerFactory;
use LaravelDoctrine\ORM\IlluminateRegistry;
use LaravelDoctrineTest\ORM\TestCase;
use Mockery as m;
use Mockery\Mock;
use RuntimeException;
use stdClass;

class IlluminateRegistryTest extends TestCase
{
    /**
     * @var Mock
     */
    protected $container;

    /**
     * @var Mock
     */
    protected $factory;

    /**
     * @var IlluminateRegistry
     */
    protected $registry;

    protected function setUp(): void
    {
        $this->container = m::mock(Container::class);
        $this->factory   = m::mock(EntityManagerFactory::class);

        $this->registry = new IlluminateRegistry(
            $this->container,
            $this->factory
        );
    }

    public function test_can_add_manager()
    {
        $this->container->shouldReceive('singleton')->twice();
        $this->registry->addManager('default', ['settings']);

        $this->assertTrue($this->registry->managerExists('default'));
    }

    public function test_can_add_connection()
    {
        $this->container->shouldReceive('singleton')->once();
        $this->registry->addConnection('default');

        $this->assertTrue($this->registry->connectionExists('default'));
    }

    public function testCanAddDefaultManager()
    {
        $this->container->shouldReceive('singleton')->times(4);
        $this->registry->addManager('default', ['settings']);
        $this->registry->addManager('second', ['settings']);
        $this->registry->setDefaultManager('second');

        $this->assertTrue($this->registry->managerExists('second'));
        $this->assertEquals('second', $this->registry->getDefaultManagerName());
    }

    public function testCanAddDefaultConnection()
    {
        $this->container->shouldReceive('singleton')->twice();
        $this->registry->addConnection('default');
        $this->registry->addConnection('second');
        $this->registry->setDefaultConnection('second');

        $this->assertEquals('second', $this->registry->getDefaultConnectionName());
    }

    public function test_get_default_connection_name()
    {
        // Will return first, when no default name
        $this->container->shouldReceive('singleton')->once();
        $this->registry->addConnection('custom');
        $this->assertEquals('custom', $this->registry->getDefaultConnectionName());

        // When default name, return default
        $this->container->shouldReceive('singleton')->once();
        $this->registry->addConnection('default');
        $this->assertEquals('default', $this->registry->getDefaultConnectionName());
    }

    public function test_get_default_manager_name()
    {
        // Will return first, when no default name
        $this->container->shouldReceive('singleton')->times(3);
        $this->registry->addManager('custom');
        $this->assertEquals('custom', $this->registry->getDefaultManagerName());

        // When default name, return default
        $this->container->shouldReceive('singleton')->once();
        $this->registry->addManager('default');
        $this->assertEquals('default', $this->registry->getDefaultManagerName());
    }

    public function test_can_get_default_connection()
    {
        $this->container->shouldReceive('singleton')->once();
        $this->registry->addConnection('default');

        $this->container->shouldReceive('make')
                        ->with('doctrine.connections.default')
                        ->andReturn('connection');

        $this->assertEquals('connection', $this->registry->getConnection());
        $this->assertEquals($this->registry->getConnection('default'), $this->registry->getConnection());
    }

    public function test_can_get_custom_connection()
    {
        $this->container->shouldReceive('singleton')->once();
        $this->registry->addConnection('custom');

        $this->container->shouldReceive('make')
                        ->with('doctrine.connections.custom')
                        ->andReturn('connection');

        $this->assertEquals('connection', $this->registry->getConnection('custom'));
    }

    public function test_cannot_non_existing_connection()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Doctrine Connection named "non-existing" does not exist.');

        $this->registry->getConnection('non-existing');
    }

    public function test_connection_gets_only_resolved_once()
    {
        $this->container->shouldReceive('singleton')->once();
        $this->registry->addConnection('default');

        $this->container->shouldReceive('make')
                        ->once()// container@make will only be called once
                        ->with('doctrine.connections.default')
                        ->andReturn('connection');

        $this->registry->getConnection();
        $this->registry->getConnection();
        $this->registry->getConnection();
        $this->registry->getConnection();
        $this->registry->getConnection();
        $this->assertEquals($this->registry->getConnection('default'), $this->registry->getConnection());
    }

    public function test_can_check_if_connection_exists()
    {
        $this->container->shouldReceive('singleton')->once();
        $this->registry->addConnection('default');

        $this->assertFalse($this->registry->connectionExists('non-existing'));
        $this->assertTrue($this->registry->connectionExists('default'));
    }

    public function test_can_get_connection_names()
    {
        $this->container->shouldReceive('singleton')->twice();

        $this->registry->addConnection('default');
        $this->registry->addConnection('custom');

        $this->assertCount(2, $this->registry->getConnectionNames());
        $this->assertContains('default', $this->registry->getConnectionNames());
        $this->assertContains('custom', $this->registry->getConnectionNames());
    }

    public function test_can_get_all_connections()
    {
        $this->container->shouldReceive('singleton')->twice();

        $this->container->shouldReceive('make')
                        ->with('doctrine.connections.default')
                        ->andReturn('connection1');

        $this->container->shouldReceive('make')
                        ->with('doctrine.connections.custom')
                        ->andReturn('connection2');

        $this->registry->addConnection('default');
        $this->registry->addConnection('custom');

        $connections = $this->registry->getConnections();

        $this->assertCount(2, $connections);
        $this->assertContains('connection1', $connections);
        $this->assertContains('connection2', $connections);
    }

    public function test_can_get_default_manager()
    {
        $this->container->shouldReceive('singleton')->times(2);
        $this->registry->addManager('default');

        $this->container->shouldReceive('make')
                        ->with('doctrine.managers.default')
                        ->andReturn('manager');

        $this->assertEquals('manager', $this->registry->getManager());
        $this->assertEquals($this->registry->getManager('default'), $this->registry->getManager());
    }

    public function test_can_get_custom_manager()
    {
        $this->container->shouldReceive('singleton')->times(2);
        $this->registry->addManager('custom');

        $this->container->shouldReceive('make')
                        ->with('doctrine.managers.custom')
                        ->andReturn('connection');

        $this->assertEquals('connection', $this->registry->getManager('custom'));
    }

    public function test_cannot_non_existing_manager()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Doctrine Manager named "non-existing" does not exist.');

        $this->registry->getManager('non-existing');
    }

    public function test_manager_gets_only_resolved_once()
    {
        $this->container->shouldReceive('singleton')->times(2);
        $this->registry->addManager('default');

        $this->container->shouldReceive('make')
                        ->once()// container@make will only be called once
                        ->with('doctrine.managers.default')
                        ->andReturn('manager');

        $this->registry->getManager();
        $this->registry->getManager();
        $this->registry->getManager();
        $this->registry->getManager();
        $this->registry->getManager();
        $this->assertEquals($this->registry->getManager('default'), $this->registry->getManager());
    }

    public function test_can_check_if_manager_exists()
    {
        $this->container->shouldReceive('singleton')->times(2);
        $this->registry->addManager('default');

        $this->assertFalse($this->registry->managerExists('non-existing'));
        $this->assertTrue($this->registry->managerExists('default'));
    }

    public function test_can_get_manager_names()
    {
        $this->container->shouldReceive('singleton')->times(4);

        $this->registry->addManager('default');
        $this->registry->addManager('custom');

        $this->assertCount(2, $this->registry->getManagerNames());
        $this->assertContains('default', $this->registry->getManagerNames());
        $this->assertContains('custom', $this->registry->getManagerNames());
    }

    public function test_can_get_all_managers()
    {
        $this->container->shouldReceive('singleton')->times(4);

        $this->container->shouldReceive('make')
                        ->with('doctrine.managers.default')
                        ->andReturn('manager1');

        $this->container->shouldReceive('make')
                        ->with('doctrine.managers.custom')
                        ->andReturn('manager2');

        $this->registry->addManager('default');
        $this->registry->addManager('custom');

        $managers = $this->registry->getManagers();

        $this->assertCount(2, $managers);
        $this->assertContains('manager1', $managers);
        $this->assertContains('manager2', $managers);
    }

    public function test_can_purge_default_manager()
    {
        $this->container->shouldReceive('singleton');
        $this->registry->addManager('default');

        $this->container->shouldReceive('forgetInstance', 'doctrine.managers.default');
        $this->container->shouldReceive('make')
            ->with('doctrine.managers.default')
            ->andReturn(m::mock(\Doctrine\Persistence\ObjectManager::class));

        $this->registry->purgeManager();
        $this->assertFalse($this->registry->managerExists('default'));
    }

    public function test_can_reset_default_manager()
    {
        $this->container->shouldReceive('singleton');
        $this->registry->addManager('default');

        $this->container->shouldReceive('forgetInstance', 'doctrine.managers.default');
        $this->container->shouldReceive('make')
            ->with('doctrine.managers.default')
            ->andReturn(m::mock(\Doctrine\Persistence\ObjectManager::class));

        $manager = $this->registry->resetManager();

        $this->assertInstanceOf(\Doctrine\Persistence\ObjectManager::class, $manager);
        $this->assertSame($manager, $this->registry->getManager());
    }

    public function test_can_purge_custom_manager()
    {
        $this->container->shouldReceive('singleton');
        $this->registry->addManager('custom');

        $this->container->shouldReceive('forgetInstance', 'doctrine.managers.custom');
        $this->container->shouldReceive('make')
            ->with('doctrine.managers.custom')
            ->andReturn(m::mock(\Doctrine\Persistence\ObjectManager::class));

        $this->registry->purgeManager();
        $this->assertFalse($this->registry->managerExists('custom'));
    }

    public function test_can_reset_custom_manager()
    {
        $this->container->shouldReceive('singleton');
        $this->registry->addManager('custom');

        $this->container->shouldReceive('forgetInstance', 'doctrine.managers.custom');
        $this->container->shouldReceive('make')
            ->with('doctrine.managers.custom')
            ->andReturn(m::mock(\Doctrine\Persistence\ObjectManager::class));

        $manager = $this->registry->resetManager('custom');

        $this->assertInstanceOf(\Doctrine\Persistence\ObjectManager::class, $manager);
        $this->assertSame($manager, $this->registry->getManager('custom'));
    }

    public function test_cannot_purge_non_existing_managers()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Doctrine Manager named "non-existing" does not exist.');

        $this->registry->purgeManager('non-existing');
    }

    public function test_cannot_reset_non_existing_managers()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Doctrine Manager named "non-existing" does not exist.');

        $this->registry->resetManager('non-existing');
    }

    public function test_get_alias_namespace_from_unknown_namespace()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Namespace "Alias" not found');

        $this->registry->getAliasNamespace('Alias');
    }

    public function test_get_alias_namespace()
    {
        $this->container->shouldReceive('singleton');
        $this->registry->addManager('default');

        $em            = m::mock(EntityManagerInterface::class);
        $configuration = m::mock(Configuration::class);

        $this->container->shouldReceive('make')
                        ->with('doctrine.managers.default')
                        ->andReturn($em);

        $em->shouldReceive('getConfiguration')->andReturn($configuration);
        $configuration->shouldReceive('getEntityNamespace')->with('Alias')->once()->andReturn('Namespace');

        $this->assertEquals('Namespace', $this->registry->getAliasNamespace('Alias'));
    }

    public function testGetRepository(): void
    {
        $this->container->shouldReceive('singleton');
        $this->registry->addManager('default');

        $entityManager = m::mock(EntityManagerInterface::class);
        $this->container->shouldReceive('make')
            ->with('doctrine.managers.default')
            ->andReturn($entityManager);

        $repository = m::mock(\Doctrine\ORM\EntityRepository::class);

        $entityManager->shouldReceive('getRepository')
            ->with('App:Entity')
            ->once()
            ->andReturn($repository);

        $this->assertEquals($repository, $this->registry->getRepository('App:Entity'));
    }

    public function testGetManagerForClass(): void
    {
        $this->container->shouldReceive('singleton');
        $this->registry->addManager('default');

        $entityManager = m::mock(EntityManagerInterface::class);
        $this->container->shouldReceive('make')
            ->with('doctrine.managers.default')
            ->andReturn($entityManager);

        $metadataFactory = m::mock(\Doctrine\ORM\Mapping\ClassMetadataFactory::class);
        $metadataFactory->shouldReceive('isTransient')
            ->with('LaravelDoctrineTest\ORM\Assets\Entity\Scientist')
            ->once()
            ->andReturnFalse();

        $metadata = m::mock(\Doctrine\Persistence\Mapping\ClassMetadata::class);
        $metadata->shouldReceive('getName')
            ->once()
            ->andReturn('LaravelDoctrineTest\ORM\Assets\Entity\Scientist');

        $metadataFactory->shouldReceive('getAllMetadata')
            ->once()
            ->andReturn([$metadata]);

        $entityManager->shouldReceive('getMetadataFactory')
            ->andReturn($metadataFactory);

        $this->assertEquals($entityManager, $this->registry->getManagerForClass('LaravelDoctrineTest\ORM\Assets\Entity\Scientist'));
    }

    public function testGetManagerForClassWithNamespace(): void
    {
        $this->container->shouldReceive('singleton');
        $this->registry->addManager('default');

        $configuration = m::mock(Configuration::class);
        $entityManager = m::mock(EntityManagerInterface::class);

        $this->container->shouldReceive('make')
            ->with('doctrine.managers.default')
            ->andReturn($entityManager);

        $configuration->shouldReceive('getEntityNamespace')
            ->with('Alias')
            ->once()
            ->andReturn('LaravelDoctrineTest\ORM\Assets\Entity');

        $entityManager->shouldReceive('getConfiguration')->andReturn($configuration);


        $metadataFactory = m::mock(\Doctrine\ORM\Mapping\ClassMetadataFactory::class);
        $metadataFactory->shouldReceive('isTransient')
            ->with('LaravelDoctrineTest\ORM\Assets\Entity\Scientist')
            ->once()
            ->andReturnFalse();

        $metadata = m::mock(\Doctrine\Persistence\Mapping\ClassMetadata::class);
        $metadata->shouldReceive('getName')
            ->once()
            ->andReturn('LaravelDoctrineTest\ORM\Assets\Entity\Scientist');

        $metadataFactory->shouldReceive('getAllMetadata')
            ->once()
            ->andReturn([$metadata]);

        $entityManager->shouldReceive('getMetadataFactory')
            ->andReturn($metadataFactory);

        $this->assertEquals($entityManager, $this->registry->getManagerForClass('Alias:Scientist'));
    }

    public function testGetManagerForClassThrowsExceptionWhenNotFound(): void
    {
        $this->expectException(RuntimeException::class);

        $this->container->shouldReceive('singleton');
        $this->registry->addManager('default');

        $entityManager = m::mock(EntityManagerInterface::class);
        $this->container->shouldReceive('make')
            ->with('doctrine.managers.default')
            ->andReturn($entityManager);

        $metadataFactory = m::mock(\Doctrine\ORM\Mapping\ClassMetadataFactory::class);
        $metadataFactory->shouldReceive('isTransient')
            ->with('LaravelDoctrineTest\ORM\Assets\Entity\Scientist')
            ->once()
            ->andReturnFalse();

        $metadata = m::mock(\Doctrine\Persistence\Mapping\ClassMetadata::class);
        $metadata->shouldReceive('getName')
            ->once()
            ->andReturn('LaravelDoctrineTest\ORM\Assets\Entity\Theory');

        $metadataFactory->shouldReceive('getAllMetadata')
            ->once()
            ->andReturn([$metadata]);

        $entityManager->shouldReceive('getMetadataFactory')
            ->andReturn($metadataFactory);

        $this->assertEquals($entityManager, $this->registry->getManagerForClass('LaravelDoctrineTest\ORM\Assets\Entity\Scientist'));
    }

    public function testGetManagerForClassInvalidClass(): void
    {
        $this->expectException(\RuntimeException::class);

        $this->container->shouldReceive('singleton');
        $this->registry->addManager('default');

        $entityManager = m::mock(EntityManagerInterface::class);
        $this->container->shouldReceive('make')
            ->with('doctrine.managers.default')
            ->andReturn($entityManager);

        $this->assertEquals($entityManager, $this->registry->getManagerForClass('LaravelDoctrineTest\ORM\Entity\ScientistInvalid'));
    }

    /**
     * Verify that getManager() returns a new instance after a call to resetManager().
     */
    public function test_get_manager_after_reset_should_return_new_manager()
    {
        $this->container->shouldReceive('singleton');
        $this->registry->addManager('default');

        $this->container->shouldReceive('make')
            ->with('doctrine.managers.default')
            ->andReturn(new stdClass(), new stdClass());

        $first = $this->registry->getManager();

        $this->container->shouldReceive('forgetInstance');
        $this->registry->resetManager();

        $second = $this->registry->getManager();
        $this->assertNotSame($first, $second);
    }

    protected function tearDown(): void
    {
        m::close();
    }
}
