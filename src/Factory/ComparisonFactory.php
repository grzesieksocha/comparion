<?php declare(strict_types=1);

namespace App\Factory;

use App\Resource\Repository;
use App\Resource\RepositoryComparison;

class ComparisonFactory
{
    public function compare(Repository $repositoryOne, Repository $repositoryTwo) : RepositoryComparison
    {
        return new RepositoryComparison($repositoryOne, $repositoryTwo);
    }
}