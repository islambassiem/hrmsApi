<?php

namespace App\Http\Controllers\Api\V1;

use Hash;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\LoginFormRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class AuthController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', only: ['logout']),
        ];
    }
    public function login(LoginFormRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'The redientails are not correct',
            ]);
        }

        $token = $user->createToken(time())->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json(
            ['message' => 'Successfully logged out']
        );
    }
}
