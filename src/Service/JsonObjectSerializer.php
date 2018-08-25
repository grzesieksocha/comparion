<?php

namespace App\Service;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class JsonObjectSerializer
{
    private $serializer;

    public function __construct()
    {
        $normalizers = [
            new GetSetMethodNormalizer(null, new CamelCaseToSnakeCaseNameConverter())
        ];
        $encoder = [new JsonEncoder()];
        $this->serializer = new Serializer($normalizers, $encoder);
    }

    public function serialize($object) : string
    {
        return $this->serializer->serialize($object, 'json');
    }
}
