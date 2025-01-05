<?php

namespace App\Services\Access;

use App\DTO\Access\Roles\StoreRoleDTO;
use App\Models\Role;
use App\DTO\GetDTO;
use Illuminate\Support\Facades\DB;

class RolesServices
{
    public function get(GetDTO $dto)
    {
        $query = Role::select('id', 'description', 'created_at', 'updated_at')
            ->with(['permissions' => function ($query) {
                $query->join('permissions as p', 'role_permission.permission_id', '=', 'p.id')
                    ->whereNotNull('p.parent')
                    ->select('role_permission.permission_id');
            }])
            ->get()
            ->map(function ($role) {
                return [
                    'id' => $role->id,
                    'description' => $role->description,
                    'created_at' => $role->created_at,
                    'updated_at' => $role->updated_at,
                    'permissions' => $role->permissions->pluck('permission_id'),
                ];
            });

        // $data = $query->orderBy('created_at', 'desc')->paginate($dto->perPage, $dto->page);
        return $query;
    }

    public function store(StoreRoleDTO $dto)
    {
        $role = new Role();
        $role->description = $dto->description;
        $role->save();
    }

    public function update(StoreRoleDTO $dto, $id)
    {
        $role = Role::find($id);
        $role->description = $dto->description;
        $role->save();
    }

    public function getPermissions()
    {
        $query = DB::table('permissions as p')
            ->select('p.id as key', 'p.description as label', 'p.parent')
            ->addSelect(
                DB::raw(
                    '
            (
                SELECT JSON_ARRAYAGG(
                    JSON_OBJECT(
                        "key", dp.id,
                        "label", dp.description,
                        "parent", dp.parent
                    )
                )
                FROM permissions as dp
                WHERE dp.parent = p.id
            ) AS children'
                )
            )
            ->whereNull('p.parent') // Filtra solo los permisos donde `parent` es NULL
            ->groupBy('p.id', 'p.description', 'p.parent')
            ->get();

        return $query;
    }
}
