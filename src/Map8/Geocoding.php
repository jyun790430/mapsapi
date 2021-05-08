<?php

namespace Jyun\Mapsapi\Map8;


/**
 * Class Geocoding
 *
 * @package Jyun\Mapsapi\Map8
 * @see https://www.map8.zone/map8-api-docs/#places-2
 */
Class Geocoding extends Service
{
    const API_URI = '/v2/place/geocode/json';

    /**
     * Geocode
     *
     * @param Client $client
     * @param string $address
     * @param array $params Query parameters: ['postcode', 'formatted_address_embed_postcode']
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function geocode(Client $client, string $address, $params=[])
    {
        $params['addresss'] = $address;

        // Show Postal code
        $params['postcode'] = $params['postcode'] ?? true;

        return self::requestHandler($client, self::API_URI, $params);
    }

    /**
     * Reverse Geocode
     *
     * @param Client $client
     * @param $latlng
     * @param array $params Query parameters: ['postcode', 'formatted_address_embed_postcode']
     * @return array|mixed $latlng ['lat', 'lng'] or latlng string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function reverseGeocode(Client $client, $latlng, $params=[])
    {
        if (is_string($latlng)) {
            $params['latlng'] = $latlng;
        } else {
            list($lat, $lng) = $latlng;
            $params['latlng'] = "{$lat},{$lng}";
        }

        // Show Postal code
        $params['postcode'] = $params['postcode'] ?? true;

        return self::requestHandler($client, self::API_URI, $params);
    }
}