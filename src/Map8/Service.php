<?php

namespace Jyun\Mapsapi\Map8;

use GuzzleHttp\Exception\GuzzleException;

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
            return $client->error($statusCode, self::$httpStatusCode[$statusCode] ?? $statusCode);

        $status = $result['status'] ?? 'OK';

        if ($status == 'OK')
            return $client->success($result['results'] ?? $result);
        else
            return $client->error(400, $status);
    }

    /**
     * Reverse multiple LatLon to LonLat
     *
     * @param $latLons
     * @return array
     */
    protected static function reverseLatLonMultiple($latLons): array
    {
        $lonLatsExplode = explode('|', $latLons);

        $lonLatsArray = [];
        foreach($lonLatsExplode as $latLon) {
            $lonLatsArray[] = self::reverseLatLon($latLon);
        }

        return $lonLatsArray;
    }

    /**
     * Reverse LatLon to LonLat
     *
     * @param string $latLon
     * @return string
     */
    protected static function reverseLatLon(string $latLon): string
    {
        $latLonExplode = explode(',', $latLon);
        $arrayReverse  = array_reverse($latLonExplode);
        $lonLat = implode(',', $arrayReverse);

        return $lonLat;
    }
}