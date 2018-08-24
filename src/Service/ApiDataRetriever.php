<?php declare(strict_types=1);

namespace App\Service;

use App\Criteria\CriteriaInterface;
use App\Resource\Repository;
use App\Resource\RepositoryFactory;

class ApiDataRetriever
{
    /** @var GithubApiCaller */
    private $githubApiCaller;
    /** @var RepositoryFactory */
    private $repositoryFactory;

    public function __construct(GithubApiCaller $githubApiCaller, RepositoryFactory $repositoryFactory)
    {
        $this->githubApiCaller = $githubApiCaller;
        $this->repositoryFactory = $repositoryFactory;
    }

    /**
     * @param Repository[] $repositories
     * @param CriteriaInterface $criteria
     * @param Fields $fields
     */
    public function fill(array $repositories, CriteriaInterface $criteria, Fields $fields)
    {
        foreach ($repositories as $repository) {
            $uri = $criteria->getUri($repository->getOwner(), $repository->getName());
            $apiResponse = $this->githubApiCaller->getData($uri);
            if ($apiResponse->isEmpty($criteria->getNoResultsConditions())) {
                return;
            }

            if ($criteria->resultAsArray()) {
                $this->repositoryFactory->setDataFromArray($repository, $apiResponse->getDecodedBody(), $fields, true);
            } else {
                $this->repositoryFactory->setData(
                    $repository,
                    $apiResponse->getBodyProperty($criteria->getItemsProperty())[0], // TODO shouldn't use table index
                    $fields
                );
            }
        }
    }
}