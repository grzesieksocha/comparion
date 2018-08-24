<?php declare(strict_types=1);

namespace App\Service;

class Fields
{
    const FORKS_COUNT = 'forks_count';
    const STARGAZERS_COUNT = 'stargazers_count';
    const UPDATED_AT = 'updated_at';
    const STATE = 'state';

    private $fields = [];

    public function addField(string $field)
    {
        $this->fields[] = $field;
    }

    public function getFields(): array
    {
        return $this->fields;
    }
}
