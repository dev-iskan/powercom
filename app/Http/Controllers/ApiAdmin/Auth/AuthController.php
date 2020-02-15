<?php

namespace App\Http\Controllers\ApiAdmin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|digits:12',
            'password' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'phone' => __('auth.failed')
                ]
            ], 422);
        }
        $token = $user->createToken($user->phone)->plainTextToken;
        return response()->json(compact('token', 'user'));
    }

    public function me()
    {
        return auth()->user();
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
