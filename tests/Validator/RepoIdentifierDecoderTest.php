<?php declare(strict_types=1);

namespace App\Tests\Validator;

use App\Validator\RepoIdentifierDecoder;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class RepoIdentifierDecoderTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider invalidIdentifiers
     */
    public function shouldNotPassInvalidIdentifier(string $identifier)
    {
        $validator = new RepoIdentifierDecoder();
        $result = $validator->decode($identifier);
        self::assertFalse($result);
    }

    /**
     * return string[]
     */
    public function invalidIdentifiers()
    {
        return [
            ['https://www.google.com/'],
            ['https://github.com/KnpLabs/php-github-api'],
            ['KnpLabs/php-github-api'],
            ['php-github-api'],
            ['myAwesomeRepository'],
            ['12'],
            ['']
        ];
    }

    /**
     * @test
     *
     * @dataProvider validIdentifiers
     */
    public function shouldPassValidIdentifier(string $identifier, array $expectedResult)
    {
        $validator = new RepoIdentifierDecoder();
        $result = $validator->decode($identifier);
        self::assertEquals($expectedResult, $result);
    }

    public function validIdentifiers()
    {
        return [
            ['elastic%2Felasticsearch', ['owner' => 'elastic', 'name' => 'elasticsearch']],
            ['https%3A%2F%2Fgithub.com%2Felastic%2Felasticsearch', ['owner' => 'elastic', 'name' => 'elasticsearch']],
            ['https%3A%2F%2Fgithub.com%2Fgoogle%2Fpython-fire', ['owner' => 'google', 'name' => 'python-fire']],
            ['google%2Fpython-fire', ['owner' => 'google', 'name' => 'python-fire']]
        ];
    }
}