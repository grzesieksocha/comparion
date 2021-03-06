<?php declare(strict_types=1);

namespace App\Resource;

class Repository
{
    private $owner;
    private $name;
    private $fields = [];

    /**
     * @param $owner
     * @param $name
     */
    public function __construct($owner, $name)
    {
        $this->owner = $owner;
        $this->name = $name;
    }

    public function getFullName()
    {
        return $this->owner . '/' . $this->name;
    }

    public function getOwner() : string
    {
        return $this->owner;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getFields() : array
    {
        return $this->fields;
    }

    public function addField($name, $value)
    {
        $this->fields[$name] = $value;
    }

    public function getField($field)
    {
        return isset($this->fields[$field]) ? $this->fields[$field] : null;
    }
}