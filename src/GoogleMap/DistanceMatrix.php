<?php

namespace Jyun\Mapsapi\GoogleMap;


/**
 * Class Distance Matrix
 *
 * @package Jyun\Mapsapi\GoogleMap
 * @see https://developers.google.com/maps/documentation/distance-matrix/overview
 */
Class DistanceMatrix extends Service
{
    const API_URI = '/maps/api/distancematrix/json';

    /**
     * Distance Matrix
     *
     * @param Client $client
     * @param $origins
     * @param $destinations
     * @param array $params Query parameters: ['mode'', 'language', 'region', 'avoid', 'units' ...]
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @see https://developers.google.com/maps/documentation/distance-matrix/overview#DistanceMatrixRequests
     */
    public static function distanceMatrix(Client $client, string $origins, string $destinations, $params=[])
    {
        $params['origins'] = $origins;
        $params['destinations'] = $destinations;

        return self::requestHandler($client, self::API_URI, $params);
    }
}