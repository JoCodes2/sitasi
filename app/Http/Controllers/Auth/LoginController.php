<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Repositories\LoginRepositories;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected $loginRepo;

    public function __construct(LoginRepositories $loginRepo)
    {
        $this->loginRepo = $loginRepo;
    }

    public function login(LoginRequest $request)
    {
        return $this->loginRepo->login($request);
    }
    public function logout(Request $request)
    {
        return $this->loginRepo->logout($request);
    }
}
