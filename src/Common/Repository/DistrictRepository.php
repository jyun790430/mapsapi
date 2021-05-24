<?php

namespace Jyun\Mapsapi\Common\Repository;

use Jyun\Mapsapi\Common\Entity\District;

Class DistrictRepository
{
    /**
     * Get District Info
     *
     * @param string $name
     * @return array
     */
    public static function getByName(string $name):array
    {
        $data = [];

        try {
            $data = District::where('name', $name)->get()->toArray();
        } catch (QueryException $queryException) {
            Log::error($queryException);
        }

        return $data;
    }
}