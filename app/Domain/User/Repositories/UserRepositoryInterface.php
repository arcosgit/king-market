<?php

namespace App\Domain\User\Repositories;

use App\Application\User\DTOs\StoreUserData;
use App\Domain\User\Entities\User;

interface UserRepositoryInterface
{
    public function store(StoreUserData $user_data): User;
}
