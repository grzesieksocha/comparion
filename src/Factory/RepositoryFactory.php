<?php declare(strict_types=1);

namespace App\Factory;

use App\Resource\Repository;
use App\Service\Fields;
use App\Validator\RepoIdentifierDecoder;
use Psr\Log\InvalidArgumentException;

class RepositoryFactory
{
    /** @var RepoIdentifierDecoder */
    private $repoIdentifierDecoder;

    public function __construct(RepoIdentifierDecoder $repoIdentifierDecoder)
    {
        $this->repoIdentifierDecoder = $repoIdentifierDecoder;
    }

    public function getRepository(string $identifier)
    {
        $identifier = $this->repoIdentifierDecoder->decode($identifier);
        if (false === $identifier) {
            throw new InvalidArgumentException('Invalid repository identifier (url encoded name / link required)');
        }
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