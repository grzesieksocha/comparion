<?php declare(strict_types=1);

namespace App\Resource;

class ComparisonFactory
{
    /**
     * @param Repository[] $repositories
     *
     * @return array
     */
    public function getComparisonByProperty($repositories): array
    {
        $fields = [];
        $result = [];

        foreach ($repositories as $repository) {
            foreach ($repository->getFields() as $fieldName => $value) {
                $fields[$fieldName] = [];
            }
        }

        foreach ($fields as $fieldName => $value) {
            foreach ($repositories as $repository) {
                $result[$fieldName][$repository->getFullName()] = $repository->getField($fieldName);
            }
        }

        return $result;
    }

    /**
     * @param Repository[] $repositories
     *
     * @return array
     */
    public function getComparisonByRepository($repositories): array
    {
        $result = [];
        foreach ($repositories as $repository) {
            foreach ($repository->getFields() as $fieldName => $value) {
                $result[$repository->getFullName()][$fieldName] = $repository->getField($fieldName);
            }
        }

        return $result;
    }
}