<?php

namespace App\Http\Controllers;

use App\Application\User\DTOs\LoginUserData;
use App\Application\User\DTOs\StoreUserData;
use App\Application\User\UseCases\AuthUserUseCase;
use App\Application\User\UseCases\StoreUserUseCase;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\StoreUserRequest;
use App\Http\Resources\User\UserResource;
use App\Infrastructure\Repositories\EloquentUserRepository;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        $data = StoreUserData::fromRequest($request);
        $user = new StoreUserUseCase(new EloquentUserRepository);
        $user = $user->execute($data);
        return UserResource::make($user)->resolve();
    }

    public function login(LoginUserRequest $request)
    {
        $data = LoginUserData::fromRequest($request);
        $user = new AuthUserUseCase(new EloquentUserRepository);
        $user = $user->execute($data);
        if(!$user){
            return response()->json(['error_password' => __('auth.password')]);
        }
        return UserResource::make($user)->resolve();
    }
}
