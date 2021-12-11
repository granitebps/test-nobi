<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $input = $request->validated();

        $input['password'] = Hash::make($input['password']);

        /** @var User $user */
        $user = User::create($input);
        $token = $user->createToken(config('app.name'));

        return Helpers::successResponse('Register Success', [
            'id' => $user->id,
            'token' => $token->plainTextToken
        ]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $input = $request->validated();

        $user = User::where('username', $input['username'])->first();

        if (!Hash::check($input['password'], $user->password)) {
            return Helpers::errorResponse('Wrong Username or Password');
        }
        $token = $user->createToken(config('app.name'));

        return Helpers::successResponse('Login Success', [
            'id' => $user->id,
            'token' => $token->plainTextToken
        ]);
    }
}
