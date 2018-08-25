<?php declare(strict_types=1);

namespace App\Resource;

class RepositoryComparison
{
    /** @var Repository */
    private $repositoryOne;
    /** @var Repository */
    private $repositoryTwo;

    public function __construct(Repository $repositoryOne, Repository $repositoryTwo)
    {
        $this->repositoryOne = $repositoryOne;
        $this->repositoryTwo = $repositoryTwo;
    }

    public function getRepositoryOne() : Repository
    {
        return $this->repositoryOne;
    }

    public function getRepositoryTwo() : Repository
    {
        return $this->repositoryTwo;
    }
}