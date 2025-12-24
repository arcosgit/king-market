<?php

namespace App\Domain\User\Exceptions;

use DomainException;

final class InvalidRoleException extends DomainException
{
    public static function notExist(int $role)
    {
        return new self("Role '{$role}' doesn't exist");
    }
}
