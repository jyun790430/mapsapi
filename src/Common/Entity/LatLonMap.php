<?php

namespace Jyun\Mapsapi\Common\Entity;

use Jenssegers\Mongodb\Eloquent\Model;

Class LatLonMap extends Model
{
    /**
     * Connection Driver Name
     *
     * @var string
     */
    protected $connection = 'mongodb';

    /**
     * Collection Name
     *
     * @var string
     */
    protected $collection = 'latlon_maps';
}