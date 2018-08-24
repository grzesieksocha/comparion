<?php declare(strict_types=1);

namespace App\Criteria;

class RepositoryCriteria implements CriteriaInterface
{
    public function getUri(string $owner, string $name): string
    {
        return '/search/repositories?q=' . $owner . '/' . $name . '+in:name+user:' . $owner;
    }

    public function getItemsProperty(): ?string
    {
        return 'items';
    }

    public function getNoResultsConditions(): array
    {
        return ['total_count' => 0];
    }

    public function resultAsArray(): bool
    {
        return false;
    }
}