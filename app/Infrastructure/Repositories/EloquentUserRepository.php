<?php

namespace App\Infrastructure\Repositories;

use App\Application\User\DTOs\StoreUserData;
use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\UserModel;
use Auth;
use Hash;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function store(StoreUserData $user_data): User
    {
        $user = UserModel::create([
            'name' => $user_data->name,
            'email' => $user_data->email,
            'lang' => $user_data->lang,
            'role_id' => 1,
            'password' => Hash::make($user_data->password),
        ]);
        Auth::login($user, true);
        return new User($user->id, $user->name, $user->email, $user->lang, $user->role_id);
    }
}
