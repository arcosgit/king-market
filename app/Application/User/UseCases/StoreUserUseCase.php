<?php

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\StoreUserData;
use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Http\Resources\User\UserResource;

final class StoreUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $users
    ){}
    public function execute(StoreUserData $userData)
    {
        $user = $this->users->store($userData);
        return UserResource::make($user)->resolve();
    }
}
