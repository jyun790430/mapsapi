<?php

namespace Jyun\Mapsapi\TwddMap;

Class Response
{
    /**
     * Success format
     *
     * @param string $source
     * @param array $data
     * @param array $trace
     * @return array
     */
    public static function success(string $source, array $data, array $trace = []):array
    {
        return [
            'source' => $source,
            'code'   => 200,
            'msg'    => 'SUCCESS',
            'data'   => $data,
            'trace'  => $trace
        ];
    }

    /**
     * Error format
     *
     * @param string $source
     * @param int $code
     * @param string $msg
     * @param array $trace
     * @return array
     */
    public static function error(string $source, int $code, string $msg = 'ERROR', array $trace = []):array
    {
        return [
            'source' => $source,
            'code'   => $code,
            'msg'    => $msg,
            'trace'  => $trace
        ];
    }
}