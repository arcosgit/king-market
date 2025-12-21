<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\ChangeLangRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // public function changeLang(ChangeLangRequest $request)
    // {
    //     $data = $request->validated();
    //     app()->setLocale($data['lang']);
    //     session(['locale' => $data['lang']]);
    //     return response()->json(['success' => true]);
    // }
}
