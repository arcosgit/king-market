<?php

namespace App\Application\User\DTOs;

use Illuminate\Http\Request;

class StoreUserData
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public string $lang,
    ){}
    public static function fromRequest(Request $request)
    {
        $data = $request->validated();
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            lang: $data['lang'],
        );
    }
}
