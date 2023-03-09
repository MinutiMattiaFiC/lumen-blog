<?php


namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request->email)->first();

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $user->createToken('MyApp')->accessToken,
            ]);
        } else {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }
    }
}
