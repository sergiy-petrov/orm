<?php

namespace LaravelDoctrineTest\ORM\Feature\Serializers;

use LaravelDoctrine\ORM\Serializers\JsonSerializer;
use LaravelDoctrineTest\ORM\Assets\Serializers\JsonableEntity;
use LaravelDoctrineTest\ORM\TestCase;

class JsonSerializerTest extends TestCase
{
    /**
     * @var JsonSerializer
     */
    protected $serializer;

    protected function setUp(): void
    {
        $this->serializer = new JsonSerializer;

        parent::setUp();
    }

    public function test_can_serialize_to_json()
    {
        $jsonableEntity = new JsonableEntity();

        $json = $this->serializer->serialize($jsonableEntity);
        $jsonableEntity->jsonSerialize();

        $this->assertEquals($jsonableEntity->toJson(), $json);

        $this->assertJson($json);
        $this->assertEquals('{"id":"IDVALUE","name":"NAMEVALUE","numeric":"1"}', $json);
    }

    public function test_can_serialize_to_json_with_numeric_check()
    {
        $json = $this->serializer->serialize(new JsonableEntity(), JSON_NUMERIC_CHECK);

        $this->assertJson($json);
        $this->assertEquals('{"id":"IDVALUE","name":"NAMEVALUE","numeric":1}', $json);
    }
}
