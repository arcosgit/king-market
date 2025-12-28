<?php

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\LoginUserData;
use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Lang;
use App\Domain\User\ValueObjects\Password;
use App\Domain\User\ValueObjects\Role;
use App\Models\UserModel;

final class AuthUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $users
    ){}
    public function execute(LoginUserData $user_data): array|bool
    {
        $user_model = UserModel::where('email', $user_data->email)->with('balance')->first();
        $user = new User($user_model->id, $user_model->name, new Email($user_data->email), new Lang($user_data->lang), new Role($user_model->role_id), new Password($user_data->password));
        $result = $this->users->auth($user);
        if($result){
            return ['user' => $user, 'balance' => $user_model->balance->amount];
        } else {
            return false;
        }

    }
}
