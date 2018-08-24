<?php declare(strict_types=1);

namespace App\Validator;

use Psr\Log\InvalidArgumentException;
use Symfony\Component\HttpFoundation\ParameterBag;

class ParametersResolver
{
    public function resolve(ParameterBag $parameters)
    {
        if (count($parameters) !== 2) {
            throw new InvalidArgumentException('Invalid number of query arguments (two required)');
        }

        $repoOneIdentifier = $this->getRepoIdentifier($parameters->get('repoOne'));
        $repoTwoIdentifier = $this->getRepoIdentifier($parameters->get('repoTwo'));

        return
            [
                'repoOne' => $repoOneIdentifier,
                'repoTwo' => $repoTwoIdentifier
            ];
    }

    private function getRepoIdentifier($parameter)
    {
        $a = explode('/', $parameter);
        $partsNum = count($a);
        if ($partsNum < 2) {
            throw new InvalidArgumentException('Invalid repository identifier (full name / link required)');
        }

        $result = [];
        $result['owner'] = $a[$partsNum - 2];
        $result['name'] = $a[$partsNum - 1];

        return $result;
    }
}