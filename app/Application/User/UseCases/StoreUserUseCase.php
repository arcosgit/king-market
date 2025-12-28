<?php

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\StoreUserData;
use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Lang;
use App\Domain\User\ValueObjects\Password;
use App\Domain\User\ValueObjects\Role;
use App\Models\BalanceModel;
use App\Models\UserModel;

final class StoreUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $users
    ){}
    public function execute(StoreUserData $user_data): User
    {
        $user = new User(null, $user_data->name, new Email($user_data->email), new Lang($user_data->lang), new Role(1), new Password($user_data->password));
        $user_id = $this->users->store($user);
        $user_model = UserModel::find($user_id);
        \Auth::login($user_model, true);
        $user->changeId($user_id);
        BalanceModel::create(['user_id' => $user->id(), 'amount' => 0]);
        return $user;
    }
}
