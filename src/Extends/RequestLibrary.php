<?php

namespace Ziyan\Ziyanco\Extends;

use GuzzleHttp\Client;

class RequestLibrary
{
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
        'Accept' => 'application/json']): array
    {
        $jsonItem = json_encode($requestParams);
        $client = new Client();
        $promise = $client->requestAsync('POST', $reqUrl, [
            'body' => $jsonItem,
            'headers' => $header
        ]);
        $response = $promise->wait();
        $response = $response->getBody()->getContents();
        $result = json_decode($response, true);
        return $result;
    }
}