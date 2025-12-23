<?php

namespace App\Domain\User\Entities;

final class User
{

    public function __construct(
        private int $id,
        private string $name,
        private string $email,
        private string $lang,
        private int $role_id,
    ){}
    public function id(){ return $this->id; }
    public function name(){ return $this->name; }
    public function email(){ return $this->email; }
    public function lang(){ return $this->lang; }
    public function roleId(){ return $this->role_id; }
}
