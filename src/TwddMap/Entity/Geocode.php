<?php

namespace Jyun\Mapsapi\TwddMap\Service\Entity;

Class Geocode
{
    /**
     * @var float|null
     */
    public $lat = null;

    /**
     * @var float|null
     */
    public $lon = null;

    /**
     * @var string|null
     */
    public $country = '';

    /**
     * @var int|null
     */
    public $zip = null;

    /**
     * @var int|null
     */
    public $city_id = null;

    /**
     * @var int|null
     */
    public $district_id = null;

    /**
     * @var string
     */
    public $city = '';

    /**
     * @var string
     */
    public $district = '';

    /**
     * @var string
     */
    public $addr = '';

    /**
     * @var string
     */
    public $address = '';
}