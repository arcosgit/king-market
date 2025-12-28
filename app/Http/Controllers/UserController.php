<?php

namespace App\Http\Controllers;

use App\Domain\User\Entities\User;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\Lang;
use App\Domain\User\ValueObjects\Role;
use App\Http\Requests\Balance\TopUpBalanceRequest;
use App\Http\Requests\User\ChangeLangRequest;
use App\Http\Requests\User\ChangeUserEmailRequest;
use App\Http\Requests\User\ChangeUserNameRequest;
use App\Http\Requests\User\ChangeUserPasswordRequest;
use App\Http\Resources\User\UserResource;
use App\Models\BalanceModel;
use App\Models\UserModel;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{

    public function profile()
    {
        return Inertia::render('user/Profile');
    }

    // public function changeLang(ChangeLangRequest $request)
    // {
    //     $data = $request->validated();
    //     app()->setLocale($data['lang']);
    //     session(['locale' => $data['lang']]);
    //     return response()->json(['success' => true]);
    // }

    public function getUser()
    {
        $userModel = UserModel::where('id', auth()->id())->with('balance')->first();
        $user = new User($userModel->id, $userModel->name, new Email($userModel->email), new Lang($userModel->lang), new Role($userModel->role_id), null);
        return response()->json([
            'user' => UserResource::make($user)->resolve(),
            'balance' => $userModel->balance->amount
        ]);
    }

    public function changeName(ChangeUserNameRequest $request)
    {
        $name = $request->validated()['name'];
        UserModel::where('id', auth()->id())->first()->update(['name' => $name]);
        return response()->json(['success' => true, 'name' => $name]);
    }

    public function changeEmail(ChangeUserEmailRequest $request)
    {
        $email = $request->validated()['email'];
        UserModel::where('id', auth()->id())->first()->update(['email' => $email]);
        return response()->json(['success' => true, 'email' => $email]);
    }

    public function changePassword(ChangeUserPasswordRequest $request)
    {
        $data = $request->validated();
        $user = UserModel::where('id', auth()->id())->first();
        if(Hash::check($data['oldPassword'], auth()->user()->password)){
            $user->password = Hash::make($data['newPassword']);
            $user->save();
            Auth::login($user, true);
            return response()->json(['success' => true]);
        } else {
            return response()->json(['error' => __('auth.password')], 422);
        }
    }

    public function topUpBalance(TopUpBalanceRequest $request)
    {
        $amount = $request->validated()['amount'];
        $balance = BalanceModel::where('user_id', auth()->id())->first();
        $balance->update(['amount' => $balance->amount + $amount]);
        return response()->json(['success' => true, 'balance' => $balance->amount]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['success' => true]);
    }

}
