<?php
namespace App\Infrastructure\Repositories;

use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Models\UserModel;
use Auth;
use Hash;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function store(User $user): int
    {
        $user = UserModel::create([
            'name' => $user->name(),
            'email' => $user->email()->getEmail(),
            'lang' => $user->lang()->getLang(),
            'role_id' => $user->roleId()->getRoleId(),
            'password' => Hash::make($user->password()->getPassword()),
        ]);
        return (int) $user->id;
    }

    public function auth(User $user): bool
    {
        if(Auth::attempt(['email' => $user->email()->getEmail(), 'password' => $user->password()->getPassword()], true)){
            return true;
        } else {
            return false;
        }
    }
}
