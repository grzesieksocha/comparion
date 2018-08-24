<?php declare(strict_types=1);

namespace App\Criteria;

class PullCriteria implements CriteriaInterface
{
    public function getUri(string $owner, string $name): string
    {
        return '/repos/' . $owner . '/' . $name . '/pulls?state=all'; // TODO missing pagination functionality
    }

    public function getItemsProperty(): ?string
    {
        return null;
    }

    public function getNoResultsConditions(): array
    {
        return [];
    }

    public function resultAsArray(): bool
    {
        return true;
    }
}