<?php declare(strict_types=1);

namespace App\Service;

use App\Criteria\CriteriaInterface;
use App\Criteria\PullCriteria;
use App\Criteria\RepositoryCriteria;
use App\Resource\Repository;

class ApiDataRetriever
{
    /** @var GithubApiCaller */
    private $githubApiCaller;

    public function __construct(GithubApiCaller $githubApiCaller)
    {
        $this->githubApiCaller = $githubApiCaller;
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
//        $this->setPullRequestData($repository);
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

        $this->fillOld($repository, new RepositoryCriteria(), $fields);
    }

    /**
     * @param Repository[] $repositories
     */
    private function setPullRequestData(Repository $repository) : void
    {
        $fields = new Fields();
        $fields->addField(Fields::STATE);

        $this->fillOld($repository, new PullCriteria(), $fields);
    }

    public function fillOld(Repository $repository, CriteriaInterface $criteria, Fields $fields) : void
    {
        $uri = $criteria->getUri($repository->getOwner(), $repository->getName());
        $apiResponse = $this->githubApiCaller->getData($uri);
        if ($apiResponse->isEmpty($criteria->getNoResultsConditions())) {
            return;
        }

        if ($criteria->resultAsArray()) {
            $this->setDataFromArray($repository, $apiResponse->getDecodedBody(), $fields, true);
        } else {
            $this->setData(
                $repository,
                $apiResponse->getBodyProperty($criteria->getItemsProperty())[0], // TODO shouldn't use table index
                $fields
            );
        }
    }

    public function setData(Repository $repository, $data, Fields $fields)
    {
        foreach ($fields->getFields() as $property => $field) {
            $repository->addField($property, $data->{$field});
        }
    }

    public function setDataFromArray(Repository $repository, $data, Fields $fields, bool $count)
    {
        $result = [];
        foreach ($data as $datum) {
            foreach ($fields->getFields() as $field) {
                if ($count) {
                    $value = $datum->{$field};
                    if (isset($result[$field][$value])) {
                        $result[$field][$value]++;
                    } else {
                        $result[$field][$value] = 1;
                    }
                } else {
                    $repository->addField($field, $data->{$field});
                }
            }
        }

        if ($count) {
            foreach ($fields->getFields() as $field) {
                $aggregatedFields = [];
                foreach ($result[$field] as $name => $value) {
                    $aggregatedFields[$name] = $value;
                }
                $repository->addField($field, $aggregatedFields);
            }
        }
    }
}