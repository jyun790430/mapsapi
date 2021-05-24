<?php

namespace Jyun\Mapsapi\TwddMap;

use Jyun\Mapsapi\TwddMap\Service\DirectionsService;

Class Directions
{
    /**
     * Directions
     *
     * @param $origin
     * @param $destination
     * @param $mode ['driving', 'walking', 'bicycling'], default='driving'
     * @return array
     */
    public static function directions(string $origin, string $destination, string $mode = 'driving'): array
    {
        $service = new DirectionsService();
        return $service->directions($origin, $destination, $mode);
    }
}