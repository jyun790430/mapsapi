<?php

namespace Jyun\Mapsapi\GoogleMap;


/**
 * Class Directions
 *
 * @package Jyun\Mapsapi\GoogleMap
 * @see https://developers.google.com/maps/documentation/directions/get-directions
 */
Class Directions extends Service
{
    const API_URI = '/maps/api/directions/json';

    /**
     * Directions
     *
     * @param Client $client
     * @param $origin
     * @param $destination
     * @param array $params Query parameters: ['mode', 'waypoints', 'alternatives', 'avoid', 'language', 'units', 'region' ...]
     * @return array|mixed
     *
     * @see https://developers.google.com/maps/documentation/directions/get-directions#DirectionsRequests
     */
    public static function directions(Client $client, string $origin, string $destination, $params=[])
    {
        $params['origin'] = $origin;
        $params['destination'] = $destination;

        return self::requestHandler($client, self::API_URI, $params);
    }
}