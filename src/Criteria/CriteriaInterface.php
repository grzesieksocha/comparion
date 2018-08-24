<?php declare(strict_types=1);

namespace App\Criteria;

interface CriteriaInterface
{
    public function getUri(string $owner, string $name): string;
    public function getItemsProperty(): ?string;
    public function getNoResultsConditions(): array;
    public function resultAsArray(): bool;
}