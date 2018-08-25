<?php declare(strict_types=1);

namespace App\Service;

class Fields
{
    public const FORKS_COUNT = ['fieldName' => 'forks', 'apiField' => 'forks_count'];
    public const STARGAZERS_COUNT = ['fieldName' => 'stars', 'apiField' => 'stargazers_count'];
    public const UPDATED_AT = ['fieldName' => 'last_update', 'apiField' => 'updated_at'];
    public const OPEN_PULL_REQUESTS = ['fieldName' => 'open_pull_requests', 'apiField' => 'open_pr'];
    public const CLOSED_PULL_REQUESTS = ['fieldName' => 'closed_pull_requests', 'apiField' => 'closed_pr'];

    private $fields = [];

    public function addField(array $field)
    {
        $this->fields[$field['fieldName']] = $field['apiField'];
    }

    public function getFields() : array
    {
        return $this->fields;
    }
}
