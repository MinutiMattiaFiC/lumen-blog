<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
class UserController extends Controller {

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

    }


}
