<?php

namespace App\Domain\User\ValueObjects;

use App\Domain\User\Exceptions\InvalidPasswordException;

final class Password
{
    public function __construct(private string $password)
    {
        if(\strlen($password) < 8){
            throw InvalidPasswordException::notEnoughSymbols();
        }
    }
    public function password()
    {
        return $this->password;
    }
}
