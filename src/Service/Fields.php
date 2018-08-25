<?php declare(strict_types=1);

namespace App\Service;

class Fields
{
    public const FORKS_COUNT = ['fieldName' => 'forks', 'apiField' => 'forks_count'];
    public const STARGAZERS_COUNT = ['fieldName' => 'stars', 'apiField' => 'stargazers_count'];
    public const UPDATED_AT = ['fieldName' => 'latest_release', 'apiField' => 'updated_at'];
    public const STATE = ['fieldName' => 'pull_requests', 'apiField' => 'state'];

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
