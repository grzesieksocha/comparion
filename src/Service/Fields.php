<?php declare(strict_types=1);

namespace App\Service;

class Fields
{
    public const FORKS_COUNT = ['forks' => 'forks_count'];
    public const STARGAZERS_COUNT = ['stars' => 'stargazers_count'];
    public const UPDATED_AT = ['latest_release' => 'updated_at'];
    public const STATE = ['pull_requests' => 'state'];

    private $fields = [];

    public function addField(array $field)
    {
        $this->fields[] = $field;
    }

    public function getFields() : array
    {
        return $this->fields;
    }
}
