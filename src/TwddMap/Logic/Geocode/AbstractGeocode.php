<?php

namespace Jyun\Mapsapi\TwddMap\Logic;

use Jyun\Mapsapi\TwddMap\Entity\Geocode;

abstract Class AbstractGeocode
{
//    /**
//     * @var array Configuration params
//     */
//    protected $setting;
//
//    /**
//     * @var array Data from the previous source
//     */
//    protected $previousSourceParams;
//
//    /**
//     * @var array Data from the previous source
//     */
//    protected $errorMsg;
//
//    /**
//     * Set global params
//     *
//     * @param array $setting
//     * @param array $previousSourceParams
//     */
//    public function setGlobalParams(array $setting, array $previousSourceParams): void
//    {
//        $this->setting = $setting;
//        $this->previousSourceParams = $previousSourceParams;
//    }

//    /**
//     * Check request has error
//     *
//     * @return bool
//     */
//    public function setError(): bool
//    {
//        return $this->errorMsg ? true : false;
//    }
//
//    /**
//     * Check request has error
//     *
//     * @return bool
//     */
//    public function getError(): bool
//    {
//        return $this->errorMsg ? true : false;
//    }

    /**
     * Validate the params
     *
     * @param string $address
     * @return bool
     */
    abstract protected function valid(string $address): bool;

    /**
     * Execute source service
     *
     * @param string $address
     * @return bool
     */
    abstract public function exec(string $address): bool;

    /**
     * Get Geocode object
     *
     * @return Geocode
     */
    abstract public function get(): Geocode;

    /**
     * Change source data format
     *
     * @param string $address
     * @return array
     */
    abstract protected function convertData(string $address):array;

    /**
     * Get error msg
     *
     * @return mixed
     */
    abstract public function error();
}