<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use stdClass as AuxClass;
use Tymon\JWTAuth\Facades\JWTAuth;

if (!function_exists('jsonLog')) {
    function jsonLog($data)
    {
        return strval(json_encode($data, JSON_PRETTY_PRINT));
    }
}