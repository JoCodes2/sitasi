<?php

namespace App\Repositories;

use App\Http\Requests\LoginRequest;
use App\Interfaces\LoginInterfaces;
use App\Models\User;
use Illuminate\Http\Request;

class LoginRepositories implements LoginInterfaces
{
    protected $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }
    public function login(LoginRequest $request)
    {
        throw new \Exception('Not implemented');
    }
    public function logout(Request $request)
    {
        throw new \Exception('Not implemented');
    }
}
