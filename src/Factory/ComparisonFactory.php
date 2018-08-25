<?php declare(strict_types=1);

namespace App\Factory;

use App\Resource\DiffSummary;
use App\Resource\Repository;
use App\Resource\RepositoryComparison;

use Github\Exception\MissingArgumentException;

class ComparisonFactory
{
    public function compare(Repository $repositoryOne, Repository $repositoryTwo) : RepositoryComparison
    {
        $diffSummary = new DiffSummary();
        foreach ($repositoryOne->getFields() as $name => $value) {
            switch ($name) {
                case 'forks':
                    $diffSummary->setForks($this->getHigherValue($repositoryOne, $repositoryTwo, $name));
                    break;
                case 'stars':
                    $diffSummary->setStars($this->getHigherValue($repositoryOne, $repositoryTwo, $name));
                    break;
                case 'last_update':
                    $diffSummary->setLastUpdate($this->getHigherValue($repositoryOne, $repositoryTwo, $name, true));
                    break;
                case 'open_pull_requests':
                    $diffSummary->setOpenPullRequests($this->getHigherValue($repositoryOne, $repositoryTwo, $name));
                    break;
                case 'closed_pull_requests':
                    $diffSummary->setClosedPullRequestes($this->getHigherValue($repositoryOne, $repositoryTwo, $name));
                    break;
                case 'latest_release':
                    $diffSummary->setLatestRelease($this->getHigherValue($repositoryOne, $repositoryTwo, $name, true));
                    break;
                default:
                    throw new MissingArgumentException('Missing argument to compare');
            }
        }
        return new RepositoryComparison($repositoryOne, $repositoryTwo, $diffSummary);
    }

    private function getHigherValue(Repository $repositoryOne, Repository $repositoryTwo, $fieldName, $isDate = false)
    {
        $valueOne = $repositoryOne->getField($fieldName);
        $valueTwo = $repositoryTwo->getField($fieldName);

        $comparableValueOne = $isDate ? new \DateTime($valueOne) : $valueOne;
        $comparableValueTwo = $isDate ? new \DateTime($valueTwo) : $valueTwo;

        if ($comparableValueOne > $comparableValueTwo) {
            return [$repositoryOne->getFullName() => $valueOne];
        } elseif ($comparableValueTwo > $comparableValueOne) {
            return [$repositoryTwo->getFullName() => $valueTwo];
        } else {
            return ['both_repo' => $valueOne];
        }
    }
}