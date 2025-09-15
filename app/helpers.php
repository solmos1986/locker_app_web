<?php

use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

if (! function_exists('jsonLog')) {
    function jsonLog($data)
    {
        return strval(json_encode($data, JSON_PRETTY_PRINT));
    }
}

if (! function_exists('VerificateRol')) {
    function VerificateRol($value): bool
    {
        $isValid = false;
        $roles   = Auth::user()->getCurrentRol;
        foreach ($roles as $key => $rol) {
            if ($rol->name == $value) {
                $isValid = true;
                return true;
            }
        }

        return $isValid;
    }
}

if (! function_exists('sizeDoor')) {
    function sizeDoor($value): string
    {
        if ($value === 1) {
            return env('SIZE_DOOR_SMALL');
        }
        if ($value === 2) {
            return env('SIZE_DOOR_MEDIUM');
        }
        if ($value === 3) {
            return env('SIZE_DOOR_LARGE');
        }
        return env('SIZE_DOOR_SMALL');
    }
}
if (! function_exists('getUser')) {
    function getUser()
    {
        JWTAuth::parseToken()->authenticate();
        return JWTAuth::getPayload();
    }
}
