<?php

namespace App\Tests\Factory;

use App\Factory\RepositoryFactory;
use App\Resource\Repository;
use App\Validator\RepoIdentifierDecoder;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class RepositoryFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreateRepository()
    {
        /** @var RepoIdentifierDecoder | PHPUnit_Framework_MockObject_MockObject $repoIdentifierDecoder */
        $repoIdentifierDecoder = $this->getMockBuilder(RepoIdentifierDecoder::class)->getMock();
        $repoIdentifierDecoder->method('decode')->willReturn(['owner' => 'adam', 'name' => 'silver']);
        $factory = new RepositoryFactory($repoIdentifierDecoder);
        $identifier = 'adam/silver';

        $repo = $factory->getRepository($identifier);

        // should probably be in sepparate tests
        self::assertInstanceOf(Repository::class, $repo);
        self::assertAttributeEquals('adam', 'owner', $repo);
        self::assertAttributeEquals('silver', 'name', $repo);
    }
}
