<?php

namespace Jyun\Mapsapi\Map8;


/**
 * Class Directions
 *
 * @package Jyun\Mapsapi\Map8
 * @see https://www.map8.zone/map8-api-docs/#api-directions-api
 */
Class Directions extends Service
{
    const API_URI = '/route/';

    /**
     * Directions (多點路徑規劃)
     *
     * @param Client $client
     * @param $origin
     * @param $destination
     * @param array $params Query parameters: ['mode', 'alternatives', 'steps', 'overview', 'geometries']
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function directions(Client $client, string $origin, string $destination, $params=[])
    {
        $latLon[] = self::reverseLatLon($origin);
        $latLon[] = self::reverseLatLon($destination);

        $mode = $params['mode'] ?? 'car'; // car, bicycle, foot

        # 格式: <起點之經度>,<起點之緯度>;<中途點之經度>,<中途點之緯度>;...;<目的地之經度>,<目的地之緯度>.json

        $uri = self::API_URI . $mode . '/' . implode(";", $latLon) . '.json';

        return self::requestHandler($client, $uri, $params);
    }

    /**
     * Reverse LatLon to LonLat
     *
     * @param string $latLon
     * @return string
     */
    protected static function reverseLatLon(string $latLon)
    {
        $latLonExplode = explode(',', $latLon);
        $arrayReverse  = array_reverse($latLonExplode);
        $lonLat = implode(',', $arrayReverse);

        return $lonLat;
    }
}