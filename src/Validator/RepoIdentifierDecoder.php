<?php declare(strict_types=1);

namespace App\Validator;

class RepoIdentifierDecoder
{
    /**
     * @return array | bool
     */
    public function decode(string $identifier)
    {
        if (!$this->checkIfEncoded($identifier)) {
            return false;
        }
        $decoded = urldecode($identifier);

        if ($this->validateStructure($decoded)) {
            return $this->getIdentifiers($decoded);
        }

        return false;
    }

    private function validateStructure($identifier)
    {
        return preg_match('/[a-zA-Z0-9\-\_]+\/(?:.(?!\/))[a-zA-Z0-9\-\_]+$/', $identifier);
    }

    /**
     * @return array | bool
     */
    private function getIdentifiers(string $parameter)
    {
        $a = explode('/', $parameter);
        $partsNum = count($a);
        if ($partsNum < 2) {
            return false;
        }

        $result = [];
        $result['owner'] = $a[$partsNum - 2];
        $result['name'] = $a[$partsNum - 1];

        return $result;
    }

    private function checkIfEncoded($identifier)
    {
        $check = urlencode(urldecode($identifier));
        return $check === $identifier;
    }
}