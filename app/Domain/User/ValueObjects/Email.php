<?php
namespace App\Domain\User\ValueObjects;

use App\Domain\User\Exceptions\InvalidEmailException;

final class Email
{

    public function __construct(private string $email){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw InvalidEmailException::incorrect($email);
        }
    }

    public function email()
    {
        return $this->email;
    }
}
