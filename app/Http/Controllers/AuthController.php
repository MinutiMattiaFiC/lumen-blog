<?php


namespace App\Http\Controllers;

use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        //insert db
        $user = DB::table('user')->insert([
            'first_name' => $request->input('first_name'),
            'last_name' => $request ->input('last_name'),
            'email' => $request ->input('email'),
            'password' =>Hash::make($request->input('password')),


        ]);
        return response()->json(['user' => $user], 201);
    }


    public function login(Request $request)
    {
        // Validate the request data
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = user::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Generate new token
                $apiToken = bin2hex(random_bytes(10));

        // Update user with new token
                $user->api_token = $apiToken;
                $user->save();

        // Return token as part of response
                return response()->json(['token' => $apiToken]);


    }



    public function check(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }


        return response()->json([
            'user' => $user,

        ]);
    }



}
