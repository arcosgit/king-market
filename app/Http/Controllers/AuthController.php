<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\StoreUserRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        return $data;
    }
}
