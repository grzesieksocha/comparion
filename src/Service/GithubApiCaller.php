<?php declare(strict_types=1);

namespace App\Service;

use App\Api\ApiResponse;

class GithubApiCaller
{
    private $apiUrl;

    public function __construct(string $apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    public function getData(string $link) : ApiResponse
    {
        $headers = [];
        $ch = curl_init();
        curl_setopt_array(
            $ch,
            [
                CURLOPT_URL => $this->apiUrl . $link,
                CURLOPT_USERAGENT => 'Comparion',
                CURLOPT_ACCEPT_ENCODING => 'application/vnd.github.v3+json',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADERFUNCTION => $this->getHeaderFunctionClosure($headers)
            ]
        );

        $data = curl_exec($ch);

        $apiResponse = new ApiResponse($headers, $data);

        curl_close($ch);
        return $apiResponse;
    }

    public function getHeaderFunctionClosure(array &$headers) // TODO created to use Link header for pagination
    {
        return function ($curl, $header) use (&$headers) {
            $len = strlen($header);
            $header = explode(':', $header, 2);
            if (count($header) < 2) {
                return $len;
            }

            $name = strtolower(trim($header[0]));
            if (!array_key_exists($name, $headers)) {
                $headers[$name] = [trim($header[1])];
            } else {
                $headers[$name][] = trim($header[1]);
            }

            return $len;
        };
    }
}