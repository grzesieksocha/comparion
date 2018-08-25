<?php declare(strict_types=1);

namespace App\Service;

use App\Resource\Repository;
use Github\Api\PullRequest;
use Github\Api\Repo;
use Github\Client;
use Github\ResultPager;

class ApiDataRetriever
{
    /** @var GithubApiCaller */
    private $githubApiCaller;
    private $client;

    public function __construct(GithubApiCaller $githubApiCaller)
    {
        $this->githubApiCaller = $githubApiCaller;

        $client = new Client();
        $client->authenticate(getenv('GITHUB_SECRET'), getenv('GITHUB_SECRET'), getenv('GITHUB_AUTH_METHOD'));
        $this->client = $client;
    }

    public function fill(Repository $repository)
    {
        $this->setRepositoryData($repository);
    }

    /**
     * @return Repository[]
     */
    private function setRepositoryData(Repository $repository) : void
    {
        $this->setBasicData($repository);
        $this->setPullRequestData($repository);
    }

    /**
     * @param Repository[] $repositories
     */
    private function setBasicData(Repository $repository) : void
    {
        $fields = new Fields();
        $fields->addField(Fields::FORKS_COUNT);
        $fields->addField(Fields::STARGAZERS_COUNT);
        $fields->addField(Fields::UPDATED_AT);

        /** @var Repo $api */
        $api = $this->client->api('repo');
        $data = $api->show($repository->getOwner(), $repository->getName());
        $this->setData($repository, $data, $fields);
    }

    /**
     * @param Repository[] $repositories
     */
    private function setPullRequestData(Repository $repository) : void
    {
        $fields = new Fields();
        $fields->addField(Fields::OPEN_PULL_REQUESTS);
        $fields->addField(Fields::CLOSED_PULL_REQUESTS);

        /** @var PullRequest $api */
        $api = $this->client->api('pull_request');
        $paginator  = new ResultPager($this->client);
        $openPullRequests = $paginator->fetchAll($api, 'all', [$repository->getOwner(), $repository->getName(), ['state' => 'open']]);
        $closedPullRequests = $paginator->fetchAll($api, 'all', [$repository->getOwner(), $repository->getName(), ['state' => 'closed']]);
        $data = [
            'open_pr' => count($openPullRequests),
            'closed_pr' => count($closedPullRequests)
        ];
        $this->setData($repository, $data, $fields);
    }

    private function setData(Repository $repository, $data, Fields $fields)
    {
        foreach ($fields->getFields() as $property => $field) {
            $repository->addField($property, $data[$field]);
        }
    }
}