<?php

namespace Jyun\Mapsapi;

Class BaseClient
{
    /**
     * Success format
     *
     * @param $data
     * @param string $msg
     * @return array
     */
    public function success($data, string $msg = ''):array
    {
        return [
            'code' => 200,
            'msg'  => ($msg) ?: 'SUCCESS',
            'data' => $data
        ];
    }

    /**
     * Error format
     *
     * @param int $code
     * @param string $msg
     * @return array
     */
    public function error(int $code, string $msg = 'ERROR'):array
    {
        return [
            'code' => $code,
            'msg'  => $msg
        ];
    }
}