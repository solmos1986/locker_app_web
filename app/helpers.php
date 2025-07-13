<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use stdClass as AuxClass;
use Tymon\JWTAuth\Facades\JWTAuth;

use function PHPUnit\Framework\isEmpty;

if (!function_exists('jsonLog')) {
    function jsonLog($data)
    {
        return strval(json_encode($data, JSON_PRETTY_PRINT));
    }
}

if (!function_exists('VerificateRol')) {
    function VerificateRol($value): bool
    {
        $isValid = false;
        $roles = Auth::user()->getCurrentRol;
        foreach ($roles as $key => $rol) {
            if ($rol->name == $value) {
                $isValid = true;
                return true;
            }
        }

        return $isValid;
    }
}
