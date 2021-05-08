<?php

namespace Jyun\Mapsapi\Map8;

abstract class Service
{
    private static $httpStatusCode = [
        400 => 'Bad Request',
        401 => 'Unauthorized',
        503 => 'Service Unavailable'
    ];

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
            return $client->error($statusCode, self::$httpStatusCode[$statusCode] ?? '');

        $status = $result['status'] ?? 'OK';
        if ($status == 'ZERO_RESULTS')
            return $client->success([]);
        elseif ($status != 'OK')
            return $client->error(400, $result['status']);

        return $client->success($result['results'] ?? $result);
    }
}