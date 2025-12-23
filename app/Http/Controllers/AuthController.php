<?php

namespace App\Http\Controllers;

use App\Application\User\DTOs\StoreUserData;
use App\Application\User\UseCases\StoreUserUseCase;
use App\Http\Requests\Auth\StoreUserRequest;
use App\Infrastructure\Repositories\EloquentUserRepository;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        $user_data = StoreUserData::fromRequest($request);
        $store_user = new StoreUserUseCase(new EloquentUserRepository);
        return $store_user->execute($user_data);
    }
}
