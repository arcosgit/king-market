<?php
namespace App\Domain\User\Exceptions;

use DomainException;

final class InvalidEmailException extends DomainException
{
    public static function incorrect(string $email)
    {
        return new self("Email '{$email}' is not correct");
    }
}
