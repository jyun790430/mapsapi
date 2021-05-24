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
     */
    public static function directions(Client $client, string $origin, string $destination, $params=[])
    {
        $latLon[] = self::reverseLatLon($origin);
        $latLon[] = self::reverseLatLon($destination);

        $mode = $params['mode'] ?? 'car'; // car, bicycle, foot

        # 是否需傳回所規劃路徑上的每一個詳細轉彎資訊
        $params['steps'] = (isset($params['steps']) && $params['steps']) ? 'true' : 'false';
        # 是否多路徑規劃
        $params['alternatives'] = (isset($params['alternatives']) && $params['alternatives'] ) ? 'true' : 'false';

        # 格式: <起點之經度>,<起點之緯度>;<中途點之經度>,<中途點之緯度>;...;<目的地之經度>,<目的地之緯度>.json

        $uri = self::API_URI . $mode . '/' . implode(";", $latLon) . '.json';

        return self::requestHandler($client, $uri, $params);
    }
}