<?php
namespace App\Infrastructure\Repositories;

use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\UserModel;
use Hash;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function store(User $user): int
    {
        $user = UserModel::create([
            'name' => $user->name(),
            'email' => $user->email(),
            'lang' => $user->lang(),
            'role_id' => $user->roleId(),
            'password' => Hash::make($user->password()),
        ]);
        return (int) $user->id;
    }
}
