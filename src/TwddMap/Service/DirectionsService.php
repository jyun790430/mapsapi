<?php

namespace Jyun\Mapsapi\TwddMap\Service;

use Jyun\Mapsapi\Common\Repository\AresRepository;
use Jyun\Mapsapi\Common\Repository\LatLonMapRepository;

Class DirectionsService extends Service
{
    private $DIRECTIONS_FORMAT = [
        'routes' => [
            'legs' => [
                'distance' => 0,
                'duration' => 0,
                'steps' => []
            ]
        ]
    ];

    private $DIRECTIONS_MODE = [
        'driving' => [
            self::SOURCE_MAP8 => 'car',
            self::SOURCE_GOOGLE_MAP => 'driving'
        ],
        'walking' => [
            self::SOURCE_MAP8 => 'foot',
            self::SOURCE_GOOGLE_MAP => 'walking'
        ],
        'bicycling' => [
            self::SOURCE_MAP8 => 'bicycle',
            self::SOURCE_GOOGLE_MAP => 'bicycling'
        ],
        'transit' => [
            self::SOURCE_MAP8 => 'car',
            self::SOURCE_GOOGLE_MAP => 'transit'
        ]
    ];

    /**
     * Directions
     *
     * @param $origin
     * @param $destination
     * @param $mode ['driving', 'walking', 'bicycling', 'transit']
     * @return array
     */
    public function directions(string $origin, string $destination, string $mode): array
    {
        $modes = $this->DIRECTIONS_MODE[$mode] ?? $this->DIRECTIONS_MODE['driving'];

        # Step1. Map8
        $params['mode'] = $modes[self::SOURCE_MAP8];
        $direction = $this->map8Client->directions($origin, $destination, $params);
        $directionHandle = $this->handle($direction, self::SOURCE_MAP8);

        if ($directionHandle !== false) {
            $direction = $this->convertDataWithMap8($directionHandle);
            return ResponseService::success(self::SOURCE_MAP8, $direction, $this->trace);
        }

        # Step2. GoogleMap
        $params['mode'] = $modes[self::SOURCE_GOOGLE_MAP];
        $direction = $this->googleMapClient->directions($origin, $destination, $params);
        $directionHandle = $this->handle($direction, self::SOURCE_GOOGLE_MAP);

        if ($directionHandle !== false) {
            $direction = $this->convertDataWithGoogleMap($directionHandle);
            return ResponseService::success(self::SOURCE_GOOGLE_MAP, $direction, $this->trace);
        }

        return ResponseService::error(self::SOURCE_GOOGLE_MAP, $direction['code'], $direction['msg'], $this->trace);
    }

    protected function handleData($data)
    {
        return $data['data'];
    }

    protected function handleCondition($data): bool
    {
        return $data['code'] == 200 && isset($data['data']) && $data['data'];
    }

    /**
     * Convert Map8 Directions to standard format
     *
     * @param array $params
     * @return array
     */
    protected function convertDataWithMap8(array $params): array
    {
        $data = $this->DIRECTIONS_FORMAT;

        $data['routes']['legs']['distance'] = $params['routes'][0]['distance'] ?? 0;
        $data['routes']['legs']['duration'] = $params['routes'][0]['duration'] ?? 0;

        foreach($params['routes'][0]['legs'] as $row) {
            $data['routes']['legs']['steps'][] = [
                'distance' => $row['distance'] ?? 0,
                'duration' => $row['duration'] ?? 0,
                'summary'  => $row['summary'] ?? ''
            ];
        }

        return $data;
    }

    /**
     * Convert GoogleMap Directions to standard format
     *
     * @param array $params
     * @return array
     */
    protected function convertDataWithGoogleMap(array $params): array
    {
        $data = $this->DIRECTIONS_FORMAT;

        $data['routes']['legs']['distance'] = $params['routes'][0]['legs'][0]['distance']['value'] ?? 0;
        $data['routes']['legs']['duration'] = $params['routes'][0]['legs'][0]['duration']['value'] ?? 0;

        foreach($params['routes'][0]['legs'][0]['steps'] as $row) {
            $data['routes']['legs']['steps'][] = [
                'distance' => $row['distance']['value'] ?? 0,
                'duration' => $row['duration']['value'] ?? 0,
                'summary'  => $row['html_instructions'] ?? ''
            ];
        }

        return $data;
    }
}