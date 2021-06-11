<?php

namespace Jyun\Mapsapi\TwddMap\Service;

use Jyun\Mapsapi\Common\Repository\AresRepository;
use Jyun\Mapsapi\Common\Repository\LatLonMapRepository;

Class GeocodingService extends Service
{
    private $GEOCODING_FORMAT = [
        'lat' => null,
        'lon' => null,
        'country' => '',
        'zip' => null,
        'city_id' => null,
        'district_id' => null,
        'city' => '',
        'district' => '',
        'addr' => '',
        'address' => '',
    ];

    /**
     * Geocode
     *
     * @param string $address
     * @return array
     */
    public function geocode(string $address): array
    {
        # Step1. Map8
        $geocode = $this->map8Client->geocode($address);
        $geocodeHandle = $this->handle($geocode, self::SOURCE_MAP8);

        if ($geocodeHandle !== false) {
            $geocode = $this->convertDataWithMap8($geocodeHandle);
            return ResponseService::success(self::SOURCE_MAP8, $geocode, $this->trace);
        }

        # Step2. GoogleMap
        $geocode = $this->googleMapClient->geocode($address);
        $geocodeHandle = $this->handle($geocode, self::SOURCE_GOOGLE_MAP);

        if ($geocodeHandle !== false) {
            $geocode = $this->convertDataWithGoogleMap($geocodeHandle);
            return ResponseService::success(self::SOURCE_GOOGLE_MAP, $geocode, $this->trace);
        }

        return ResponseService::error(self::SOURCE_GOOGLE_MAP, $geocode['code'], $geocode['msg'], $this->trace);
    }

    /**
     * Reverse Geocode
     *
     * @param string $latlon
     * @return array
     */
    public function reverseGeocode(string $latlon): array
    {
        # Step1. Mongo
        $latLonMapRepository = new LatLonMapRepository();
        $geocode = $latLonMapRepository->getWithLatLon($latlon);
        if (isset($geocode['msg']) || !$geocode) {
            $this->setTrace(self::SOURCE_MONGO, (!$geocode) ? 'ZERO_RESULTS' : $geocode['msg']);
        } else {
            $geocode = $this->convertDataWithMongo($geocode);
            return ResponseService::success(self::SOURCE_MONGO, $geocode, $this->trace);
        }

        # Step2. Map8
        $geocode = $this->map8Client->reverseGeocode($latlon);
        $geocodeHandle = $this->handle($geocode, self::SOURCE_MAP8);

        if ($geocodeHandle !== false) {
            $geocode = $this->convertDataWithMap8($geocodeHandle);
            return ResponseService::success(self::SOURCE_MAP8, $geocode, $this->trace);
        }

        # Step3. GoogleMap
        $geocode = $this->googleMapClient->reverseGeocode($latlon);
        $geocodeHandle = $this->handle($geocode, self::SOURCE_GOOGLE_MAP);

        if ($geocodeHandle !== false) {
            $geocode = $this->convertDataWithGoogleMap($geocodeHandle);
            return ResponseService::success(self::SOURCE_GOOGLE_MAP, $geocode, $this->trace);
        }

        return ResponseService::error(self::SOURCE_GOOGLE_MAP, $geocode['code'], $geocode['msg'], $this->trace);
    }

    protected function handleData($data)
    {
        return $data['data'][0];
    }

    protected function handleCondition($data): bool
    {
        return $data['code'] == 200 && isset($data['data'][0]);
    }

    /**
     * Convert Mongo Geocode to standard format
     *
     * @param array $params
     * @return array
     */
    protected function convertDataWithMongo(array $params): array
    {
        $data = $this->GEOCODING_FORMAT;

        $data['lat'] = (float) $params['latlon'][1] ?? null;
        $data['lon'] = (float) $params['latlon'][0] ?? null;
        $data['zip'] = (int) $params['zip'] ?? null;
        $data['city_id'] = $params['city_id'] ?? null;
        $data['district_id'] = $params['district_id'] ?? null;
        $data['city'] = $params['city'] ?? '';
        $data['district'] = $params['district'] ?? '';
        $data['addr'] = $params['addr'] ?? '';
        $data['address'] = $params['address'] ?? '';

        return $data;
    }

    /**
     * Convert Map8 Geocode to standard format
     *
     * @param array $params
     * @return array
     */
    protected function convertDataWithMap8(array $params): array
    {
        $data = $this->GEOCODING_FORMAT;

        $data['lat'] = (float) $params['geometry']['location']['lat'] ?? null;
        $data['lon'] = (float) $params['geometry']['location']['lng'] ?? null;
        $data['zip'] = (int) $params['postcode'] ?? null;
        $data['city'] = $params['city'] ?? '';
        $data['district'] = $params['town'] ?? '';
        $data['addr'] = $params['name'] ?? '';
        $data['address'] = $data['zip'] . $params['formatted_address'] ?? '';

        return $this->convertArea($data);
    }

    /**
     * Convert GoogleMap Geocode to standard format
     *
     * @param array $params
     * @return array
     */
    protected function convertDataWithGoogleMap(array $params): array
    {
        $data = $this->GEOCODING_FORMAT;

        $data['lat'] = $params['geometry']['location']['lat'] ?? null;
        $data['lon'] = $params['geometry']['location']['lng'] ?? null;
        $data['address'] = $params['formatted_address'] ?? '';

        $route = '';
        $number = '';
        $country = '';
        $addressComponents = $params['address_components'];
        foreach($addressComponents as $row) {
            switch ($row['types'][0]) {
                case 'country':
                    $country = $row['short_name'];
                case 'administrative_area_level_1':
                    $data['city'] = $row['short_name'];
                    break;
                case 'administrative_area_level_3':
                    $data['district'] = $row['short_name'];
                    break;
                case 'postal_code':
                    $data['zip'] = (int) $row['short_name'];
                    break;
                case 'route':
                    $route = $row['short_name'];
                    break;
                case 'street_number':
                    $number = $row['short_name'] . '號';
            }
        }

        $data['country'] = $country;
        $data['addr'] = $route . $number;

        return $this->convertArea($data);
    }

    /**
     * Convert Area Info
     *
     * @param array $params
     * @return array
     */
    private function convertArea(array $params):array
    {
        if ($params['zip']) {
            $data = AresRepository::getByZip((int) $params['zip']);
        } else {
            $data = AresRepository::getByCityDistrict($params['city'], $params['district']);
        }

        $params['zip'] = ($params['zip']) ?: ($data['zip'] ?? null);
        $params['city_id'] = $params['city_id'] ?: ($data['city_id'] ?? null);
        $params['district_id'] = $params['district_id'] ?: ($data['district_id'] ?? null);

        # mongo, map8: Uncertain country origin
        if (!$params['country']) {
            $params['country'] = $this->isTaiwanArea($params) ? 'TW' : '';
        }

        return $params;
    }

    /**
     * Check is taiwan area
     *
     * @param array $params
     * @return bool
     */
    private function isTaiwanArea(array $params): bool
    {
        $lat = $params['lat'];
        $lon = $params['lon'];

        if (!$lat || !$lon) {
            return false;
        }
        if($lat > 25.29 || $lat < 21.5350) {
            return false;
        }
        if($lon > 121.995 || $lon < 119.86) {
            return false;
        }

        return true;
    }
}