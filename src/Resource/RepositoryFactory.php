<?php

namespace App\Resource;

use App\Service\Fields;

class RepositoryFactory
{
    public function getRepository($identifier)
    {
        return new Repository($identifier['owner'], $identifier['name']);
    }

    public function setData(Repository $repository, $data, Fields $fields)
    {
        foreach ($fields->getFields() as $methodName => $field) {
            $repository->addField($field, $data->{$field});
        }
    }

    public function setDataFromArray(Repository $repository, $data, Fields $fields, bool $count)
    {
        $result = [];
        foreach ($data as $datum) {
            foreach ($fields->getFields() as $field) {
                if ($count) {
                    $value = $datum->{$field};
                    if (isset($result[$field][$value])) {
                        $result[$field][$value]++;
                    } else {
                        $result[$field][$value] = 1;
                    }
                } else {
                    $repository->addField($field, $data->{$field});
                }
            }
        }

        if ($count) {
            foreach ($fields->getFields() as $field) {
                $aggregatedFields = [];
                foreach ($result[$field] as $name => $value) {
                    $aggregatedFields[$name] = $value;
                }
                $repository->addField($field, $aggregatedFields);
            }
        }
    }
}