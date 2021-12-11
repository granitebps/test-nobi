<?php

namespace App\Http\Controllers\Api\v1;

use App\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(StoreRequest $request): JsonResponse
    {
        $input = $request->validated();

        $input['password'] = Hash::make($input['password']);

        /** @var User $user */
        $user = User::create($input);
        $token = $user->createToken(config('app.name'));

        return Helpers::successResponse('Store User Success', [
            'id' => $user->id,
            'token' => $token->plainTextToken
        ]);
    }
}
