<?php

namespace Jyun\Mapsapi\GoogleMap;

use GuzzleHttp\Exception\GuzzleException;

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
     */
    protected static function requestHandler(Client $client, $uri, $params, $method='GET')
    {
        try {
            $response = $client->request($uri, $params, $method);
            $result = $response->getBody()->getContents();
            $result = json_decode($result, true);
        } catch (GuzzleException $e) {
            return $client->error('500', $e->getMessage());
        }

        $statusCode = $response->getStatusCode();

        if (200 != $statusCode)
            return $client->error($statusCode, $result['error_message'] ?? '');

        $status = $result['status'] ?? 'There is no status field to describe';

        if ($status == 'OK')
            return $client->success($result['results'] ?? $result);
        elseif (isset($result['error_message']))
            return $client->error(400, $result['error_message']);
        else
            return $client->error(400, $status);
    }
}