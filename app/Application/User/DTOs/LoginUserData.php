<?php

namespace App\Application\User\DTOs;

use Illuminate\Http\Request;

class LoginUserData
{
    public function __construct(
        public string $email,
        public string $password,
        public string $lang,
    ){}
    public static function fromRequest(Request $request)
    {
        $data = $request->validated();
        return new self(
            email: $data['email'],
            password: $data['password'],
            lang: $data['lang'],
        );
    }
}
