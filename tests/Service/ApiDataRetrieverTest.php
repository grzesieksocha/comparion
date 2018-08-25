<?php

namespace App\Tests\Service;

use App\Resource\Repository;
use App\Service\ApiDataRetriever;
use Github\Api\PullRequest;
use Github\Api\Repo;
use Github\Api\Repository\Releases;
use Github\Client;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Psr\Http\Message\ResponseInterface;

class ApiDataRetrieverTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFillRepositoryWithBasicData()
    {
        /** @var Releases | PHPUnit_Framework_MockObject_MockObject $releases */
        $releases = $this->getMockBuilder(Releases::class)->disableOriginalConstructor()->getMock();

        /** @var Repo | PHPUnit_Framework_MockObject_MockObject $api */
        $repoApi = $this->getMockBuilder(Repo::class)->disableOriginalConstructor()->getMock();
        $repoApi->expects(self::any())->method('releases')->willReturn($releases);
        $repoApi->expects(self::any())->method('show')->willReturn([
            'forks_count' => 15,
            'stargazers_count' => 123,
            'updated_at' => '2018-08-23T15:43:14Z'
        ]);

        /** @var PullRequest | PHPUnit_Framework_MockObject_MockObject $api */
        $pullRequestApi = $this->getMockBuilder(PullRequest::class)->disableOriginalConstructor()->getMock();

        /** @var ResponseInterface | PHPUnit_Framework_MockObject_MockObject $responseInterface */
        $responseInterface = $this->getMockBuilder(ResponseInterface::class)->getMock();

        /** @var Client | PHPUnit_Framework_MockObject_MockObject $githubClient */
        $githubClient = $this->getMockBuilder(Client::class)->getMock();
        $githubClient->expects(self::any())->method('getLastResponse')->willReturn($responseInterface);
        $githubClient->expects(self::any())->method('api')->willReturnMap([
            ['repo', $repoApi],
            ['pull_request', $pullRequestApi]
        ]);

        /** @var Repository | PHPUnit_Framework_MockObject_MockObject $repository */
        $repository = new Repository('owner', 'name');

        $apiRetriever = new ApiDataRetriever($githubClient);

        $apiRetriever->fill($repository);

        self::assertAttributeNotEmpty('fields', $repository);
    }
}