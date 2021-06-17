<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Events\UserHasCreated;
use App\Mail\UserEmail;

class UserAuthController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api', ['except' => ['authenticate', 'register', 'signup']]);
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required | email',
            'password' => 'required | string | min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = Auth::attempt($validator->validated())) {
            return response()->json(['error' => 'Oops! wrong password'], 401);
        }


        return $this->createNewToken($token);
    }

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required | email | unique:users'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->all();
        $user = new User;
        $user->email = $data['email'];
        $user->name = $data['name'];
        $user->password = Hash::make('password');
        $user->save();

        $credentials = ["email"=>$data['email'], "password"=>'password'];
        $token = Auth::attempt($credentials);

        if ($token)
        {
            UserHasCreated::dispatch(["name" => $data['name'], "email" => $data['email']]);
            return $this->createNewToken($token);
        } else {
            return response()->json(['error' => 'Error en el proceso de signup'], 401);
        }
    }

    public function logout() {
        Auth::logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }
}
