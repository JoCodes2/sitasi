<?php

namespace App\Repositories;

use App\Http\Requests\LoginRequest;
use App\Interfaces\LoginInterfaces;
use App\Models\User;
use App\Traits\HttpResponseTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginRepositories implements LoginInterfaces
{
    use HttpResponseTraits;
    protected $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }
    public function login(LoginRequest $request)
    {
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Unauthorized'
                ], 401);
            } else {
                $user = $this->userModel::where('email', $request->email)->first();
                $user->createToken('token')->plainTextToken;
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login success',
                ]);
            }
        } catch (\Throwable $th) {
            return $this->error(
                $th->getMessage(),
                400,
                $th,
                class_basename($this),
                __FUNCTION__
            );
        }
    }



    public function logout(Request $request)
    {
        try {
            $request->user('web')->tokens()->delete();

            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return $this->success();
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400, $th, class_basename($this), __FUNCTION__);
        }
    }
}
