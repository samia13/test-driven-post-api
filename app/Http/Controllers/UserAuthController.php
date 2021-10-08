<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
 
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
 
        $token = $user->createToken('passportToken')->accessToken;
 
        return response()->json(['token' => $token]);
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        
        // api guard has no attempt method, wich lead us to use web guard 
        if (Auth::guard('web')->attempt($credentials)) {
            $token = Auth::guard('web')->user()->createToken('passportToken')->accessToken;
            return response()->json(['token' => $token]);
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }
    }
}
