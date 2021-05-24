<?php

namespace Jyun\Mapsapi\Map8;


/**
 * Class Distance Matrix
 *
 * @package Jyun\Mapsapi\Map8
 */
Class DistanceMatrix extends Service
{
    const API_URI = '/distancematrix/';

    /**
     * Distance Matrix (排班) 多點運算交通時間距離矩陣
     *
     * @param Client $client
     * @param $origins
     * @param $destinations
     * @param array $params
     * @return array|mixed
     */
    public static function distanceMatrix(Client $client, string $origins, string $destinations, $params=[])
    {
        $origins = self::reverseLatLonMultiple($origins);
        $destinations = self::reverseLatLonMultiple($destinations);

        $latLon = array_merge($origins, $destinations);

        $mode = $params['mode'] ?? 'car'; // car, bicycle, foot

        $uri = self::API_URI . $mode . '/' . implode(";", $latLon) . '.json';

        $params['sourceIndices'] = 0;
        $params['destinationIndices'] = implode(',' ,range(1, count($destinations)));

        return self::requestHandler($client, $uri, $params);
    }
}