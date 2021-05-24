<?php

namespace Jyun\Mapsapi\TwddMap\Service;

Class ResponseService
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
        $data = [
            'source' => $source,
            'code'   => 200,
            'msg'    => 'SUCCESS',
            'data'   => $data,
        ];

        if ($trace) {
            $data['trace'] = $trace;
        }

        return $data;
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
        $data = [
            'source' => $source,
            'code'   => $code,
            'msg'    => $msg,
        ];

        if ($trace) {
            $data['trace'] = $trace;
        }

        return $data;
    }
}