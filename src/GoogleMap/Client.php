<?php

namespace Jyun\Mapsapi\GoogleMap;

use GuzzleHttp\Client as HttpClient;
use Jyun\Mapsapi\BaseClient;

/**
 * Class Client
 *
 * @package Jyun\Mapsapi\GoogleMap
 */
Class Client extends BaseClient
{
    /**
     * Google Maps base API URL
     */
    const API_URL = 'https://maps.googleapis.com';

    /**
     * For service autoload
     */
    const SERVICE_NAMESPACE = "\\Jyun\\Mapsapi\\GoogleMap\\";

    protected static $serviceMethodMap = [
        //'placeSearch'       => 'PlaceSearch',
        //'placeNearBySearch' => 'PlaceSearch',
        //'placeTextSearch'   => 'PlaceSearch',
        //'placeDetail'       => 'PlaceDetail',
        //'placeAutoComplete' => 'PlaceAutoComplete',
        'directions'          => 'Directions',
        'distanceMatrix'      => 'DistanceMatrix',
        'geocode'             => 'Geocoding',
        'reverseGeocode'      => 'Geocoding',
    ];

    /**
     * Google API KEY
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Google Maps default language
     *
     * @var string
     */
    protected $language = 'zh-TW';

    /**
     * Google Maps default timezone
     *
     * @var string
     */
    protected $timeout  = 2;

    /**
     * GuzzleHttp\Client
     *
     * @var GuzzleHttp\Client
     */
    protected $httpClient;

    public function __construct($params = [])
    {
        if (is_string($params)) {
            $key = $params;
            $params = [];
            $params['key'] = $key;
        }

        $key  = $params['key'] ?? env('MAPSAPI_GOOGLE_API_KEY');
        $lang = $params['language'] ?? null;
        $timeout = $params['timeout'] ?? null;

        $this->apiKey = (string) $key;

        if ($lang) {
            $this->setLanguage($lang);
        }

        if ($timeout) {
            $this->setTimeout($timeout);
        }

        // Load GuzzleHttp\Client
        $this->httpClient = new HttpClient([
            'base_uri' => self::API_URL,
            'timeout'  => $this->timeout,
        ]);

        return $this;
    }

    /**
     * Setting Language
     *
     * @param string $lang
     * @return Client
     */
    public function setLanguage(string $lang): self
    {
        $this->language = $lang;

        return $this;
    }

    /**
     * Setting HttpClient Timeout Second
     *
     * @param int $timeout
     * @return Client
     */
    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Request Google Map API
     *
     * @param string $uri
     * @param array $params
     * @param string $method
     * @param null $body
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     */
    public function request(string $uri, array $params=[], string $method='GET', $body=null)
    {
        // Guzzle request options
        $options = [
            'http_errors' => false,
        ];

        $defaultParams = [
            'key'      => $this->apiKey,
            'language' => $this->language
        ];

        $options['query'] = array_merge($defaultParams, $params);

        if ($body) {
            $options['body'] = $body;
        }

        return $this->httpClient->request($method, $uri, $options);
    }

    /**
     * Client methods refer to each service
     *
     * @param $method
     * @param $arguments
     * @return mixed
     * @throws \Exception
     */
    public function __call($method, $arguments)
    {
        if (!isset(self::$serviceMethodMap[$method]))
            throw new \Exception("Call to undefined method ".__CLASS__."::{$method}()", 400);

        $service = self::$serviceMethodMap[$method];

        array_unshift($arguments, $this);

        return call_user_func_array([self::SERVICE_NAMESPACE . $service, $method], $arguments);
    }
}