<?php declare(strict_types=1);

namespace App\Resource;

class RepositoryComparison
{
    /** @var Repository */
    private $repositoryOne;
    /** @var Repository */
    private $repositoryTwo;
    /** @var DiffSummary */
    private $diffSummary;

    public function __construct(Repository $repositoryOne, Repository $repositoryTwo, DiffSummary $diffSummary)
    {
        $this->repositoryOne = $repositoryOne;
        $this->repositoryTwo = $repositoryTwo;
        $this->diffSummary = $diffSummary;
    }

    public function getRepositoryOne() : Repository
    {
        return $this->repositoryOne;
    }

    public function getRepositoryTwo() : Repository
    {
        return $this->repositoryTwo;
    }

    public function getDiffSummary(): DiffSummary
    {
        return $this->diffSummary;
    }
}