<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class ChangeCompanyService
{
    public function __construct() {}

    public function changeCompany()
    {
        Log::info("ChangeCompanyService changeCompany ");
        $companies = DB::table('client')->get();
        return $companies;
    }

    public function updateMovement()
    {
        Log::info("MovementService updateMovement");
    }
}
