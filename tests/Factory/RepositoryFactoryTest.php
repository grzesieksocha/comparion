<?php

namespace App\Tests\Factory;

use App\Factory\RepositoryFactory;
use App\Resource\Repository;
use PHPUnit\Framework\TestCase;

class RepositoryFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreateRepository()
    {
        $factory = new RepositoryFactory();
        $identifier = ['owner' => 'adam', 'name' => 'silver'];
        $repo = $factory->getRepository($identifier);

        // should probably be in sepparate tests
        self::assertInstanceOf(Repository::class, $repo);
        self::assertAttributeEquals('adam', 'owner', $repo);
        self::assertAttributeEquals('silver', 'name', $repo);
    }
}
