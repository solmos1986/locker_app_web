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

if (! function_exists('getLocker')) {
    function getLocker()
    {
        JWTAuth::parseToken()->authenticate();
        return JWTAuth::getPayload();
    }
}

if (! function_exists('getDoor')) {
    function getDoor()
    {
        return [
            [
                'name'  => '1',
                'order' => 1,
            ],
            [
                'name'  => '2',
                'order' => 2,
            ],
            [
                'name'  => '3',
                'order' => 3,
            ],
            [
                'name'  => '4',
                'order' => 4,
            ],
            [
                'name'  => '5',
                'order' => 5,
            ],
            [
                'name'  => '6',
                'order' => 6,
            ],
            [
                'name'  => '7',
                'order' => 7,
            ],
            [
                'name'  => '8',
                'order' => 8,
            ],
            [
                'name'  => '9',
                'order' => 9,
            ],
            [
                'name'  => '10',
                'order' => 10,
            ],
            [
                'name'  => '11',
                'order' => 11,
            ],
            [
                'name'  => '12',
                'order' => 12,
            ],
            [
                'name'  => '13',
                'order' => 13,
            ],
            [
                'name'  => '14',
                'order' => 14,
            ],
            [
                'name'  => '15',
                'order' => 15,
            ],
            [
                'name'  => '16',
                'order' => 16,
            ],
            [
                'name'  => '17',
                'order' => 17,
            ],
            [
                'name'  => '18',
                'order' => 18,
            ],
            [
                'name'  => '19',
                'order' => 19,
            ],
            [
                'name'  => '20',
                'order' => 20,
            ],
            [
                'name'  => '21',
                'order' => 21,
            ],
            [
                'name'  => '22',
                'order' => 22,
            ],
            [
                'name'  => '23',
                'order' => 23,
            ],
            [
                'name'  => '24',
                'order' => 24,
            ],
            [
                'name'  => '25',
                'order' => 25,
            ],
            [
                'name'  => '26',
                'order' => 26,
            ],
            [
                'name'  => '27',
                'order' => 27,
            ],
        ];
    }
}

if (! function_exists('getComandOpen')) {
    function getComandOpen()
    {
        return [
            [
                'comand' => '8a0101119b',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a01021198',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a01031199',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a0104119e',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a0105119f',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a0106119c',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a0107119d',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a01081192',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a01091193',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a010a1190',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a010b1191',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a010c1196',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a010d1197',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a010e1194',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a010f1195',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a0110118a',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a0111118b',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a01121188',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a01131189',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a0114118e',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a0115118f',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a0116118c',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a0117118d',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a01181182',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a01191183',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a011a1180',
                'name'   => 'abrir',
            ],
            [
                'comand' => '8a011b1181',
                'name'   => 'abrir',
            ],
        ];
    }
}

if (! function_exists('getComandRead')) {
    function getComandRead()
    {
        return [
            [
                'comand' => '80010133b3',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80010233b0',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80010333b1',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80010433b6',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80010533b7',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80010633b4',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80010733b5',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80010833ba',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80019933bb',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80010a33b8',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80010b33b9',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80010c33be',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80010d33bf',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80010e33bc',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80010f33bd',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80011033a2',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80011133a3',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80011233a0',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80011333a1',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80011433a6',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80011533a7',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80011633a4',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80011733a5',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80011833aa',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80011933ab',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80011a33a8',
                'name'   => 'lectura',
            ],
            [
                'comand' => '80011b33a9',
                'name'   => 'lectura',
            ],
        ];
    }
}

if (! function_exists('getComandOpened')) {
    function getComandOpened()
    {
        return [
            [
                'comand' => '8001011191',
                'name'   => 'abierto',
            ],
            [
                'comand' => '8001021192',
                'name'   => 'abierto',
            ],
            [
                'comand' => '8001031193',
                'name'   => 'abierto',
            ],
            [
                'comand' => '8001041194',
                'name'   => 'abierto',
            ],
            [
                'comand' => '8001051195',
                'name'   => 'abierto',
            ],
            [
                'comand' => '8001061196',
                'name'   => 'abierto',
            ],
            [
                'comand' => '8001071197',
                'name'   => 'abierto',
            ],
            [
                'comand' => '8001081198',
                'name'   => 'abierto',
            ],
            [
                'comand' => '8001091199',
                'name'   => 'abierto',
            ],
            [
                'comand' => '80010a119a',
                'name'   => 'abierto',
            ],
            [
                'comand' => '80010b119b',
                'name'   => 'abierto',
            ],
            [
                'comand' => '80010c119c',
                'name'   => 'abierto',
            ],
            [
                'comand' => '80010d119d',
                'name'   => 'abierto',
            ],
            [
                'comand' => '80010e119e',
                'name'   => 'abierto',
            ],
            [
                'comand' => '80010f119f',
                'name'   => 'abierto',
            ],
            [
                'comand' => '8001101180',
                'name'   => 'abierto',
            ],
            [
                'comand' => '8001111181',
                'name'   => 'abierto',
            ],
            [
                'comand' => '8001121182',
                'name'   => 'abierto',
            ],
            [
                'comand' => '8001131183',
                'name'   => 'abierto',
            ],
            [
                'comand' => '8001141184',
                'name'   => 'abierto',
            ],
            [
                'comand' => '8001151185',
                'name'   => 'abierto',
            ],
            [
                'comand' => '8001161186',
                'name'   => 'abierto',
            ],
            [
                'comand' => '8001171187',
                'name'   => 'abierto',
            ],
            [
                'comand' => '8001181188',
                'name'   => 'abierto',
            ],
            [
                'comand' => '8001191189',
                'name'   => 'abierto',
            ],
            [
                'comand' => '80011a118a',
                'name'   => 'abierto',
            ],
            [
                'comand' => '80011b118b',
                'name'   => 'abierto',
            ],
        ];
    }
}

if (! function_exists('getComandClosed')) {
    function getComandClosed()
    {
        return [
            [
                'comand' => '8001010080',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '8001020083',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '8001030082',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '8001040085',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '8001050084',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '8001060087',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '80010700b6',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '8001080089',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '8001090088',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '80010a008b',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '80010b008a',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '80010c008d',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '80010d008c',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '80010e008f',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '80010f008e',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '8001100091',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '8001110090',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '8001120093',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '8001130092',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '8001140095',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '8001150094',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '8001160097',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '8001170096',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '8001180099',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '8001190098',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '80011a009b',
                'name'   => 'cerrado',
            ],
            [
                'comand' => '80011b009a',
                'name'   => 'cerrado',
            ],
        ];
    }
}