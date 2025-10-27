<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class ApiIntegrationService
{
    public function __construct()
    {}

    public function dataTable(
        $pageSize,
        $pageIndex,
        $active,
        $direction,
        $building_id,
        $search
    ) {
        Log::info("ApiIntegrationService dataTable " . jsonLog([
            $pageSize,
            $pageIndex,
            $active,
            $direction,
            $building_id,
            $search,
        ]));
        $dataTable = new stdClass();
        $apiIntegrations = DB::table('api_integration')
            ->select(
                'api_integration.api_integration_id',
                'api_integration.building_id',
                'api_integration.api_url',
                'api_integration.name_function',
                'api_integration.description',
                'api_integration.time_ejecution',
                'api_integration.is_job',
            )
            ->where('api_integration.building_id', $building_id)
            ->paginate($pageSize);

        $dataTable->paginate = [
            'length'    => $apiIntegrations->count(),
            'pageIndex' => $pageIndex,
            'pageSize'  => $pageSize,
        ];
        $dataTable->sort = [
            'active'    => $active,
            'direction' => $direction,
        ];
        $dataTable->apiIntegrations = $apiIntegrations->items();
        return $dataTable;
    }

    public function store(
        $building_id,
        $company_id,
        $name,
        $code,
        $address,
        $manager,
        $phone
    ) {
        Log::info("ApiIntegrationService store " . jsonLog([
            $building_id,
            $company_id,
            $name,
            $code,
            $address,
            $manager,
            $phone,
        ]));
        $building = DB::table('building')
            ->insertGetId(
                [
                    'building_id' => $building_id,
                    'company_id'  => $company_id,
                    'name'        => $name,
                    'code'        => $code,
                    'address'     => $address,
                    'manager'     => $manager,
                    'phone'       => $phone,
                ]
            );
        return $building;
    }

    public function edit($building_id)
    {
        Log::info("ApiIntegrationService update " . jsonLog([$building_id]));
        $building = DB::table('building')
            ->select(
                'building.building_id',
                'building.company_id',
                'building.name',
                'building.code',
                'building.manager',
                'building.phone',
                'building.address'
            )
            ->where('building.building_id', $building_id)
            ->first();
        return $building;
    }

    public function update(
        $building_id,
        $company_id,
        $name,
        $code,
        $address,
        $manager,
        $phone
    ) {
        Log::info("ApiIntegrationService update " . jsonLog([
            $building_id,
            $company_id,
            $name,
            $code,
            $address,
            $manager,
            $phone,
        ]));
        $building = DB::table('building')
            ->where('building.building_id', $building_id)
            ->update(
                [
                    'company_id' => $company_id,
                    'name'       => $name,
                    'code'       => $code,
                    'address'    => $address,
                    'manager'    => $manager,
                    'phone'      => $phone,
                ]
            );
        return $building;
    }

    public function delete($building_id)
    {
        $building = DB::table('building')
            ->where('building.building_id', $building_id)
            ->delete();
        return $building;
    }
}
