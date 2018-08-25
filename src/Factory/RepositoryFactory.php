<?php declare(strict_types=1);

namespace App\Factory;

use Psr\Log\InvalidArgumentException;

use App\Resource\Repository;
use App\Validator\RepoIdentifierDecoder;

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
}