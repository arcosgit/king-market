<?php

namespace App\Http\Controllers;

use App\Domain\User\Entities\User;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Lang;
use App\Domain\User\ValueObjects\Role;
use App\Http\Requests\User\ChangeLangRequest;
use App\Http\Resources\User\UserResource;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    // public function changeLang(ChangeLangRequest $request)
    // {
    //     $data = $request->validated();
    //     app()->setLocale($data['lang']);
    //     session(['locale' => $data['lang']]);
    //     return response()->json(['success' => true]);
    // }

    public function getUser()
    {
        $user = UserModel::find(auth()->id())->first();
        $user = new User($user->id, $user->name, new Email($user->email), new Lang($user->lang), new Role($user->role_id), null);
        return UserResource::make($user)->resolve();
    }

    public function profile()
    {
        return Inertia::render('user/Profile');
    }
}
