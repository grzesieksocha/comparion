<?php

namespace App\Tests\Service;

use App\Resource\Repository;
use App\Service\ApiDataRetriever;
use App\Service\GithubApiCaller;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;

class ApiDataRetrieverTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFillRepositoryWithBasicData()
    {
        /** @var GithubApiCaller | PHPUnit_Framework_MockObject_MockObject $githubApiCaller */
        $githubApiCaller = $this->getMockBuilder(GithubApiCaller::class)->disableOriginalConstructor()->getMock();
        /** @var Repository | PHPUnit_Framework_MockObject_MockObject $repository */
        $repository = $this->getMockBuilder(Repository::class)->disableOriginalConstructor()->getMock();
        $apiRetriever = new ApiDataRetriever($githubApiCaller);

        $apiRetriever->fill($repository);

        self::assertAttributeNotEquals([], 'fields', $repository);
    }
}