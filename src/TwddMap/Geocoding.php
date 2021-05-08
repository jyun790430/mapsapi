<?php

namespace Jyun\Mapsapi\TwddMap;

use Jyun\Mapsapi\Map8\Client as Map8Client;
use Jyun\Mapsapi\GoogleMap\Client as GoogleMapClient;

Class Geocoding
{
    private static $SOURCE_MAP8 = 'Map8';
    private static $SOURCE_GOOGLE_MAP = 'GoogleMap';

    private static $GEOCODINGG_FORMAT = [
        'lat' => '',
        'lon' => '',
        'zip' => '',
        'city' => '',
        'district' => '',
        'address' => '',
    ];

    /**
     * Geocode
     *
     * @param string $address
     * @return array
     */
    public static function geocode(string $address): array
    {
        $trace = [];

        # Step1. Map8
        $Map8Client = new Map8Client();
        $geocode = $Map8Client->geocode($address);

        if ($geocode['code'] == 200 && isset($geocode['data'][0])) {

            $geocode = $geocode['data'][0];
            $geocode = self::convertDataWithMap8($geocode);

            return Response::success(self::$SOURCE_MAP8, $geocode);

        } else {
            $trace[self::$SOURCE_MAP8] = $geocode['msg'];
        }

        # Step2. GoogleMap
        $GoogleMapClient = new GoogleMapClient();
        $geocode = $GoogleMapClient->geocode($address);

        if ($geocode['code'] == 200 && isset($geocode['data'][0])) {

            $geocode = $geocode['data'][0];
            $geocode = self::convertDataWithGoogleMap($geocode);

            return Response::success(self::$SOURCE_GOOGLE_MAP, $geocode, $trace);
        }

        $trace[self::$SOURCE_GOOGLE_MAP] = $geocode['msg'];


        return Response::error(self::$SOURCE_GOOGLE_MAP, $geocode['code'], $geocode['msg'], $trace);
    }

    /**
     * Reverse Geocode
     *
     * @param string $address
     * @return array
     */
    public static function reverseGeocode(string $address): array
    {
        $trace = [];

        # Step1. Map8
        $Map8Client = new Map8Client();
        $geocode = $Map8Client->reverseGeocode($address);

        if ($geocode['code'] == 200 && isset($geocode['data'][0])) {

            $geocode = $geocode['data'][0];
            $geocode = self::convertDataWithMap8($geocode);

            return Response::success(self::$SOURCE_MAP8, $geocode);

        } else {
            $trace[self::$SOURCE_MAP8] = $geocode['msg'];
        }

        # Step2. GoogleMap
        $GoogleMapClient = new GoogleMapClient();
        $geocode = $GoogleMapClient->reverseGeocode($address);

        if ($geocode['code'] == 200 && isset($geocode['data'][0])) {

            $geocode = $geocode['data'][0];
            $geocode = self::convertDataWithGoogleMap($geocode);

            return Response::success(self::$SOURCE_GOOGLE_MAP, $geocode, $trace);
        }

        $trace[self::$SOURCE_GOOGLE_MAP] = $geocode['msg'];


        return Response::error(self::$SOURCE_GOOGLE_MAP, $geocode['code'], $geocode['msg'], $trace);
    }

    /**
     * Convert Map8 Geocode to standard format
     *
     * @param array $params
     * @return array
     */
    protected static function convertDataWithMap8(array $params): array
    {
        $data = self::$GEOCODINGG_FORMAT;

        $data['lat'] = $params['geometry']['location']['lat'] ?? '';
        $data['lon'] = $params['geometry']['location']['lng'] ?? '';
        $data['zip'] = $params['postcode'] ?? '';
        $data['city'] = $params['city'] ?? '';
        $data['district'] = $params['town'] ?? '';
        $data['address'] = $params['formatted_address'] ?? '';

        return $data;
    }


    /**
     * Convert GoogleMap Geocode to standard format
     *
     * @param array $params
     * @return array
     */
    protected static function convertDataWithGoogleMap(array $params): array
    {
        $data = self::$GEOCODINGG_FORMAT;

        $data['lat'] = $params['geometry']['location']['lat'] ?? '';
        $data['lon'] = $params['geometry']['location']['lng'] ?? '';
        $data['address'] = $params['formatted_address'] ?? '';

        $addressComponents = $params['address_components'];
        foreach($addressComponents as $row) {
            switch ($row['types'][0]) {
                case 'administrative_area_level_1':
                    $data['city'] = $row['short_name'];
                    break;
                case 'administrative_area_level_3':
                    $data['district'] = $row['short_name'];
                    break;
                case 'postal_code':
                    $data['zip'] = $row['short_name'];
                    break;
            }
        }

        return $data;
    }
}