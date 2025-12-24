<?php

namespace App\Domain\User\Exceptions;

use DomainException;

final class InvalidPasswordException extends DomainException
{
    public static function notEnoughSymbols()
    {
        return new self("The password must be at least 8 characters long.");
    }
}
