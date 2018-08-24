<?php declare(strict_types=1);

namespace App\Resource;

class ApiResponse
{
    /** @var string[] */
    private $headers;
    private $body;

    /**
     * @param string[] $headers
     */
    public function __construct(array $headers, string $body)
    {
        $this->headers = $headers;
        $this->body = json_decode($body);
    }

    public function getHeader($name): ?string
    {
        return isset($this->headers[$name]) ? $this->headers[$name] : null;
    }

    public function getDecodedBody()
    {
        return $this->body;
    }

    public function getBodyProperty(string $property)
    {
        return $this->body->{$property};
    }

    public function isEmpty(array $noResultCondition): bool
    {
        if (empty($this->body)) {
            return true;
        }

        foreach ($noResultCondition as $field => $condition) {
            if ($this->getBodyProperty($field) === $condition) {
                return true;
            }
        }

        return false;
    }
}