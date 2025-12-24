<?php
namespace App\Domain\User\ValueObjects;

use App\Domain\User\Exceptions\InvalidRoleException;


final class Role
{
    private const ROLES_IDS = [1, 2];

    public function __construct(private int $role)
    {
        if(!\in_array($role, self::ROLES_IDS, true)){
            throw InvalidRoleException::notExist($role);
        }
    }

    public function role()
    {
        return $this->role;
    }
}
