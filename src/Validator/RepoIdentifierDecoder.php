<?php declare(strict_types=1);

namespace App\Validator;

class RepoIdentifierDecoder
{
    /**
     * @return array | bool
     */
    public function decode(string $identifier)
    {
        $identifier = urldecode($identifier);
        if ($this->validateStructure($identifier)) {
            return $this->getIdentifiers($identifier);
        }

        return false;
    }

    private function validateStructure(string $identifier) : bool
    {
        return (bool) preg_match('/[a-zA-Z0-9\-\_]+\/(?:.(?!\/))[a-zA-Z0-9\-\_]+$/', $identifier);
    }

    /**
     * @return array | bool
     */
    private function getIdentifiers(string $parameter) : array
    {
        $a = explode('/', $parameter);
        $partsNum = count($a);

        $result = [];
        $result['owner'] = $a[$partsNum - 2];
        $result['name'] = $a[$partsNum - 1];

        return $result;
    }
}