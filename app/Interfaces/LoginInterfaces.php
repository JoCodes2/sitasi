<?php

namespace App\Interfaces;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

interface LoginInterfaces
{
    public function login(LoginRequest $request);
    public function logout(Request $request);
}
