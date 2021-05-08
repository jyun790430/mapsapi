<?php

namespace Jyun\Mapsapi\GoogleMap;

abstract class Service
{
    /**
     * Define by each service
     *
     * @param string
     */
    const API_URI = '';

    /**
     * Request Handler
     *
     * @param Client $client
     * @param $uri
     * @param $params
     * @param string $method
     * @return mixed|array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected static function requestHandler(Client $client, $uri, $params, $method='GET')
    {
        $response = $client->request($uri, $params, $method);
        $result = $response->getBody()->getContents();
        $result = json_decode($result, true);

        $statusCode = $response->getStatusCode();
        if (200 != $statusCode)
            return $client->error($statusCode, $result['error_message'] ?? '');
        elseif (isset($result['error_message']))
            return $client->error(400, $result['error_message']);

        return $client->success($result['results'] ?? $result);
    }
}