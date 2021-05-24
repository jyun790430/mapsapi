<?php

namespace Jyun\Mapsapi\Common\Repository;

use Jyun\Mapsapi\Common\Entity\District;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

Class AresRepository
{
    /**
     * Get Area Info
     *
     * @param int $postcode
     * @return array
     */
    public static function getByZip(int $postcode):array
    {
        $data = [];

        try {
            $data = District::whereRaw('1=1')
            ->select(
                'city.id as city_id',
                'district.id as district_id',
                'zip'
            )
            ->join('city', 'district.city_id', '=', 'city.id')
            ->where('zip', $postcode)
            ->first();

            $data = ($data) ? $data->toArray() : [];

        } catch (QueryException $queryException) {
            Log::error($queryException);
        }

        return $data;
    }

    /**
     * Get Area Info
     *
     * @param string $city
     * @param string $district
     * @return array
     */
    public static function getByCityDistrict(string $city, string $district):array
    {
        $data = [];

        try {
            $data = District::whereRaw('1=1')
                ->select(
                    'city.id as city_id',
                    'district.id as district_id',
                    'zip'
                )
                ->join('city', 'district_city.id', '=', 'city.id')
                ->where('city.name', $city)
                ->where('district.name', $district)
                ->first();

            $data = ($data) ? $data->toArray() : [];

        } catch (QueryException $queryException) {
            Log::error($queryException);
        }

        return $data;
    }
}