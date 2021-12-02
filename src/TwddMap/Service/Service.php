<?php

namespace Jyun\Mapsapi\TwddMap\Service;

use Jyun\Mapsapi\Map8\Client as Map8Client;
use Jyun\Mapsapi\GoogleMap\Client as GoogleMapClient;

abstract class Service
{
    const SOURCE_MAP8 = 'Map8';
    const SOURCE_MONGO = 'Mongo';
    const SOURCE_GOOGLE_MAP = 'GoogleMap';

    /**
     * @var Map8Client|null
     */
    protected $map8Client = null;

    /**
     * @var GoogleMapClient|null
     */
    protected $googleMapClient = null;

    /**
     * Trace error message with source response
     *
     * @var array
     */
    protected $trace = [];

    /**
     * Service constructor.
     */
    public function __construct()
    {
        $this->map8Client = new Map8Client();
        $this->googleMapClient = new GoogleMapClient();
    }

    /**
     * Set Trace
     *
     * @param string $source
     * @param string $msg
     */
    public function setTrace(string $source, string $msg): void
    {
        $this->trace[$source] = $this->trace[$source] ?? $msg;
    }

    /**
     * Return handle data
     *
     * @param $data
     * @return mixed
     */
    abstract protected function handleData($data);

    /**
     * Return handle data condition
     *
     * @param $data
     * @return bool
     */
    abstract protected function handleCondition(&$data, string $source): bool;

    /**
     * Handel handleCondition and handleData
     *
     * @param array $data
     * @param string $source
     * @return bool
     */
    public function handle(array $data, string $source)
    {
        if ($this->handleCondition($data, $source)) {
            return $this->handleData($data);
        }

        $this->setTrace($source, $data['msg']);

        return false;
    }
}