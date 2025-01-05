<?php

namespace App\Services\People;

use App\DTO\People\Dependent\StoreDependentDTO;
use App\DTO\GetDTO;
use App\Models\Dependent;
use Illuminate\Support\Facades\DB;

class DependentServices
{
    public function get(GetDTO $dto)
    {
        // $query = User::selectFields();
        $query = Dependent::select(
            'id as id',
            'id_departamento as departmentId',
            'pais as country',
            'nombre as firstName',
            'apellido as lastName',
            'sexo as gender',
            'direccion as address',
            'correo_electronico as email',
            'celular as phone',
            'fecha_nacimiento as birthdate',
            'numero_documento as documentNumber',
            'fecha_inscripcion as enrollmentDate',
            'updated_at as updatedAt'
        );


        if ($dto->search) {
            $query->where(function ($query) use ($dto) {
                $query->where('nombre', 'like', '%' . $dto->search . '%')
                    ->orWhere('apellido', 'like', '%' . $dto->search . '%')
                    ->orWhere('numero_documento', 'like', '%' . $dto->search . '%');
            });
        }

        $data = $query->orderBy('id', 'desc')->paginate($dto->perPage, $dto->page);

        return $data;
    }

    public function store(StoreDependentDTO $dto)
    {
        $dependent = new Dependent();
        $dependent->name = $dto->description;
        $dependent->save();
    }

    public function update(StoreDependentDTO $dto, $id)
    {
        $dependent = Dependent::find($id);
        $dependent->name = $dto->description;
        // $dependent->status = $dto->status == false ? "inactive" : "active";
        $dependent->save();
    }

    public function getRoles()
    {
        // return Role::select('id as value', 'description as label')->get();
    }
}
