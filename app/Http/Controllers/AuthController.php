<?php


namespace App\Http\Controllers;

use App\User;
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

            'api_token' => bin2hex(random_bytes(10)) //genera una stringa casuale di 10 caratteri esadecimali
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

        // Attempt to authenticate the user
        $credentials = $request->only('email', 'password');
        if (Auth::guard('api')->attempt($credentials)) {
            // Generate new api token
            $apiToken = bin2hex(random_bytes(10));

            // Update the user's api token in the database
            $user = Auth::guard('api')->user();
            $user->api_token = $apiToken;
            $user->save();

            // Return the api token
            return response()->json(['api_token' => $apiToken], 200);
        } else {
            return response()->json(['error' => 'Invalid email or password'], 401);
        }
    }



    public function check(): \Illuminate\Http\JsonResponse
    {
        return response()->json(Auth::user());
    }

}
