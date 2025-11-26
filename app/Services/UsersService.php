<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use stdClass;

class UsersService
{
    public function __construct()
    {}

    public function dataTable($locker_id, $pageSize, $pageIndex, $active, $direction, $search)
    {
        Log::info("UsersService/dataTable " . jsonLog([$pageSize, $pageIndex, $active, $direction, $search]));
        $dataTable = new stdClass();
        $users     = DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.celular',
                'users.email',
                DB::raw("GROUP_CONCAT( DISTINCT rol.name SEPARATOR ', ') as roles")
            )
            ->join('users_rol', 'users.id', 'users_rol.users_id')
            ->join('rol', 'rol.rol_id', 'users_rol.rol_id')
            ->groupBy(
                'users.id',
                'users.name',
                'users.celular',
                'users.email'
            )
            ->orderBy($active, $direction)
            ->paginate($pageSize);

        Log::info("UsersService dataTable users " . jsonLog($users->items()));
        $dataTable->paginate = [
            'length'    => $users->count(),
            'pageIndex' => $pageIndex,
            'pageSize'  => $pageSize,
        ];
        $dataTable->sort = [
            'active'    => $active,
            'direction' => $direction,
        ];
        $dataTable->users = $users->items();
        return $dataTable;
    }
    public function requeriment()
    {
        Log::info("UsersService requirement " . jsonLog([]));
        $roles = DB::table('rol')
            ->select(
                'rol_id',
                'name'
            )
            ->orderBy('name', 'ASC')
            ->get();
        $buildings = DB::table('building')
            ->select(
                'building.building_id',
                'building.company_id',
                'building.name',
                'building.address',
                'building.phone',
                'building.manager',
                'building.code'
            )
            ->get();
        return [
            'roles'     => $roles,
            'buildings' => $buildings,
        ];
    }
    public function storeUser(
        $name,
        $email,
        $celular,
        $password,
        $roles,
        $buildings
    ) {
        Log::info("UsersService storeUser " . jsonLog([
            $name,
            $email,
            $password,
            $roles,
            $buildings,
        ]));
        $new_password = Hash::make($password);

        $user_id = DB::table('users')
            ->insertGetId([
                'name'     => $name,
                'email'    => $email,
                'celular'  => $celular,
                'password' => $new_password,
            ]);
        $user_company = DB::table('user_company')
            ->insertGetId([
                'users_id'   => $user_id,
                'company_id' => getUser()->get('company_id'),
            ]);
        $delete = DB::table('users_rol')
            ->where('users_id', $user_id)
            ->delete();
        foreach ($roles as $key => $rol) {
            $inserUsertRol = DB::table('users_rol')
                ->insertGetId([
                    'rol_id'   => $rol,
                    'users_id' => $user_id,
                ]);
            foreach ($buildings as $key => $building) {
                $insertUserRolBuilding = DB::table('users_rol_building')
                    ->insert([
                        'users_rol_id' => $inserUsertRol,
                        'building_id'  => $building,
                    ]);
            }
        }

        return $user_id;
    }
    public function editUser($id)
    {
        Log::info("UsersService editUser " . jsonLog([$id]));
        $user = DB::table('users')
            ->select(
                'users.id',
                'users.name',
                'users.celular',
                'users.email',
                DB::raw("'' as password"),
                DB::raw("GROUP_CONCAT( DISTINCT rol.rol_id SEPARATOR ', ') as roles")
            )
            ->join('users_rol', 'users.id', 'users_rol.users_id')
            ->join('rol', 'rol.rol_id', 'users_rol.rol_id')
            ->where('users.id', $id)
            ->groupBy(
                'users.id',
                'users.name',
                'users.email',
                'users.celular',
            )->first();

        $buildings = DB::table('building')
            ->select(
                'building.building_id'
            )
            ->join('users_rol_building', 'building.building_id', 'users_rol_building.building_id')
            ->join('users_rol', 'users_rol.users_rol_id', 'users_rol_building.users_rol_id')
            ->where('users_rol.users_id', $id)
            ->get()
            ->pluck('building_id');

        $user->reset_password = false;
        $user->roles          = explode(',', $user->roles);
        $user->roles          = array_map('intval', $user->roles);
        $user->buildings      = $buildings;
        return $user;
    }
    public function updateUser(
        $users_id,
        $name,
        $email,
        $celular,
        $password,
        $roles,
        $buildings
    ) {
        Log::info("UsersService storeUser " . jsonLog([
            $users_id,
            $name,
            $email,
            $celular,
            $password,
            $roles,
            $buildings,
        ]));

        $new_password = Hash::make($password);

        $update = DB::table('users')
            ->where('id', $users_id)
            ->update([
                'name'     => $name,
                'email'    => $email,
                'celular'  => $celular,
                'password' => $new_password,
            ]);

        $users_rol = DB::table('users_rol')
            ->where('users_id', $users_id)
            ->get();

        foreach ($users_rol as $key => $user_rol) {
            $deleteUserRolBuilding = DB::table('users_rol_building')
                ->where('users_rol_id', $user_rol->users_rol_id)
                ->delete();
        }

        $delete = DB::table('users_rol')
            ->where('users_id', $users_id)
            ->delete();

        foreach ($roles as $key => $rol) {
            $inserUsertRol = DB::table('users_rol')
                ->insertGetId([
                    'rol_id'   => $rol,
                    'users_id' => $users_id,
                ]);
            foreach ($buildings as $key => $building) {
                $insertUserRolBuilding = DB::table('users_rol_building')
                    ->insert([
                        'users_rol_id' => $inserUsertRol,
                        'building_id'  => $building,
                    ]);
            }
        }
        return $update;
    }

    public function deleteUser($id)
    {
        Log::info("UsersService delete " . jsonLog([$id]));

        $users_rol = DB::table('users_rol')
            ->where('users_id', $id)
            ->get();

        foreach ($users_rol as $key => $user_rol) {
            $deleteUserRolBuilding = DB::table('users_rol_building')
                ->where('users_rol_id', $user_rol->users_rol_id)
                ->delete();
        }

        $users_rol = DB::table('users_rol')
            ->where('users_id', $id)
            ->delete();

        $user_company = DB::table('user_company')
            ->where('users_id', $id)
            ->delete();

        $user = DB::table('users')
            ->where('id', $id)
            ->delete();

        return $user;
    }
}
