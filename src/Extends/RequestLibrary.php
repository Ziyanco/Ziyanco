<?php

namespace Ziyanco\Library\Extends;

use GuzzleHttp\Client;

class RequestLibrary
{
    const TYPE_JSON = 1;
    const TYPE_BUILD_QUERY = 2;

    /**
     * GET 请求
     * @param string $url
     * @param array $requestParams
     * @param array $header
     * @return array
     */
    public static function requestGetResultJsonData(string $reqUrl, array $requestParams = [], array $header = []): array
    {
        $client = new Client();
        $url = $reqUrl . "?" . http_build_query($requestParams);
        $promise = $client->requestAsync('GET', $url,
            [
                'headers' => $header
            ]);
        $response = $promise->wait();
        $response = $response->getBody()->getContents();
        $res = json_decode($response, true);
        return $res;
    }

    /**
     * POST 请求
     * @param string $url
     * @param array $requestParams
     * @param array $header
     * @return array
     */
    public static function requestPostResultJsonData(string $reqUrl, array $requestParams = [], array $header = ['Content-Type' => 'application/json; charset=UTF-8',
        'Accept' => 'application/json'],                    $type = RequestLibrary::TYPE_JSON): array
    {
        $body = static::getBody($requestParams, $type);
        $client = new Client();
        $promise = $client->requestAsync('POST', $reqUrl, [
            'body' => $body,
            'headers' => $header
        ]);
        $response = $promise->wait();
        $response = $response->getBody()->getContents();
        $result = json_decode($response, true);
        return $result;
    }

    private static function getBody($params = [], $type)
    {
        $body = '';
        switch ($type) {
            case RequestLibrary::TYPE_JSON:
                $body = json_encode($params);
                break;
            case RequestLibrary::TYPE_BUILD_QUERY:
                $body = http_build_query($params);
                break;
        }
        return $body;
    }
}