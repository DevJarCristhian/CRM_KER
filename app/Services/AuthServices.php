<?php

namespace App\Services;

use App\Models\User;
use App\DTO\Auth\CreateTokenAuthDTO;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthServices
{
    public function createToken(CreateTokenAuthDTO $dto)
    {
        $user = User::where('email', $dto->email)->first();
        if (!$user || !Hash::check($dto->password, $user->password)) {
            return null;
        }
        Log::info("User: $user->name logged in");
        $token = $user->createToken('auth_token')->plainTextToken;
        Log::info("No paso Token: $token");
        return $token;
    }

    public function getUser()
    {
        $data = User::selectFields()
            ->with('role')
            ->where('status', 'active')
            ->where('id', auth()->user()->id)
            ->get()
            ->map(function ($user) {
                $permissions = DB::table('role_permission')
                    ->join('permissions', 'role_permission.permission_id', '=', 'permissions.id')
                    ->where('role_permission.role_id', $user->role_id)
                    ->select('permissions.name', 'permissions.parent')
                    ->get();

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role->description,
                    'permissions' => $permissions,
                ];
            });

        return $data->first();
    }

    public function logout()
    {
        DB::table('personal_access_tokens')
            ->where('tokenable_id', auth()->user()->id)
            ->delete();
    }
}
