<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Resources\UserResource;

class AuthenticationController extends ApiController
{
    public function register(RegisterRequest $request)
    {
        try {
            $request['password'] = Hash::make($request['password']);
            $user = User::create($request->toArray());
            $token = $user->createToken('userWallet')->accessToken;
            $user['token'] = $token;
            $response = [
                'success' => true,
                'status' => 200,
                'data' => new UserResource($user),
                'message' => 'User is created successfully.'
            ];
            return $this->showMessage($response, 200);
        } catch (\Exception $e) {
            \Log::error('Register ' . $e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken('userWallet')->accessToken;
                    $user['token'] = $token;
                    $response = [
                        'success' => true,
                        'status' => 200,
                        'data' => new UserResource($user),
                        'message' => 'User is logged in successfully.'
                    ];
                    return $this->showMessage($response, 200);
                } else {
                    return $this->error("Password mismatch", 422);
                }
            } else {
                return $this->error('User does not exist', 422);
            }
        } catch (\Exception $e) {
            \Log::error('Login ' . $e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $token = $request->user()->token();
            $token->revoke();
            $response = ['message' => 'You have been successfully logged out!'];
            return $this->showMessage($response, 200);
        } catch (\Exception $e) {
            \Log::error('Logout ' . $e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }

    public function userProfile(Request $request)
    {
        try {
            $user = User::with('credit', 'debit')->where('id', $request->user()->id)->first();
            $response = [
                'success' => true,
                'status' => 200,
                'data' => new UserResource($user),
                'message' => 'User fetched in successfully.'
            ];
            return $this->showMessage($response, 200);
        } catch (\Exception $e) {
            \Log::error('User Profile ' . $e->getMessage());
            return $this->error($e->getMessage(), 500);
        }
    }
}
