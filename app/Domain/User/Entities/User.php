<?php

namespace App\Domain\User\Entities;

use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Lang;
use App\Domain\User\ValueObjects\Password;
use App\Domain\User\ValueObjects\Role;

final class User
{

    public function __construct(
        private ?int $id,
        private string $name,
        private Email $email,
        private Lang $lang,
        private Role $role_id,
        private ?Password $password,
    ){}
    public function id(){ return $this->id; }
    public function name(){ return $this->name; }
    public function email(){ return $this->email->email(); }
    public function lang(){ return $this->lang->lang(); }
    public function roleId(){ return $this->role_id->role(); }
    public function password(){ return $this->password->password(); }

    public function changeId(int $id)
    {
        $this->id = $id;
    }
}
