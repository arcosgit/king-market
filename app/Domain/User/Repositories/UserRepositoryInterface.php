<?php
namespace App\Domain\User\Repositories;

use App\Domain\User\Entities\User;

interface UserRepositoryInterface
{
    public function store(User $user): int;
    public function auth(User $user): bool;
}
