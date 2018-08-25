<?php

namespace App\Tests\Service;

use App\Service\JsonObjectSerializer;
use PHPUnit\Framework\TestCase;

class JsonObjectSerializerTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSerializeObjectToJson()
    {
        $serializer = new JsonObjectSerializer();
        $hero = new class {
            public function getName()
            {
                return 'John';
            }

            public function getSurname()
            {
                return 'Doe';
            }

            public function getHeroAlias()
            {
                return 'Deadpool';
            }
        };

        $expectedSerializedHero = '{"name":"John","surname":"Doe","hero_alias":"Deadpool"}';
        $serializedHero = $serializer->serialize($hero);

        self::assertEquals($expectedSerializedHero, $serializedHero);
    }
}
