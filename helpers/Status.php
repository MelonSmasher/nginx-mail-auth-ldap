<?php

/**
 * Class Status
 */
class Status
{
    /**
     * when called the script exits
     */
    public static function fail()
    {
        header("Auth-Status: Invalid login or password");
        exit;
    }

    /**
     * @param $server
     * @param $port
     */
    public static function pass($server, $port)
    {
        header("Auth-Status: OK");
        header("Auth-Server: $server");
        header("Auth-Port: $port");
        exit;
    }
}