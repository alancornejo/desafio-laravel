<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Events\UserHasCreated;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\CatalogController;

class UserAuthController extends Controller
{

    public function __construct() {
        Log::debug('Constructor  UserAuthController');
        $this->middleware('auth:api', ['except' => ['authenticate', 'register', 'signup']]);
    }

    public function authenticate(Request $request)
    {
        Log::debug('UserAuthController -> authenticate',  $request->all());

        $validator = Validator::make($request->all(), [
            'email' => 'required | email | exists:users',
            'password' => 'required | string | min:6',
        ]);

        if ($validator->fails()) {
            Log::error('Validator Fails');
            Log::error($validator->errors());
            return response()->json($validator->errors(), 422);
        }

        if (! $token = Auth::attempt($validator->validated())) {
            Log::info('Oops! wrong password');
            return response()->json(['error' => 'Oops! wrong password'], 401);
        }

        Log::info('Autenticación exitosa');
        return $this->createNewToken($token);
    }

    public function signup(Request $request)
    {
        Log::debug('UserAuthController -> signup',  $request->all());

        $validator = Validator::make($request->all(), [
            'email' => 'required | email | unique:users'
        ]);

        if ($validator->fails()) {
            Log::error('Validator Fails');
            Log::error($validator->errors());
            return response()->json($validator->errors(), 422);
        }

        /**
         * Obtener un valor random de la columna Supplier
         */
        $catalog = New CatalogController();
        $supplierRandom = $catalog->listUniqueSuppliers();

        /**
         * Obtener los valores del Request y armar el Objeto para guardar el USER
         */
        $data = $request->all();
        $user = new User;
        $user->email = $data['email'];
        $user->name = $data['name'];
        $user->password = Hash::make('password');
        $user->supplier = $supplierRandom;
        $user->save();

        /**
         * El usuario creado validarlo con AUTH para con ello obtener el Token
         */
        $credentials = ["email"=>$data['email'], "password"=>'password'];
        $token = Auth::attempt($credentials);

        /**
         * Si el usuario fue creado exitosamente debemos obtener el token
         */
        if ($token)
        {
            Log::debug('Validación exitosa creando evento para enviar correo');
            UserHasCreated::dispatch(["name" => $data['name'], "email" => $data['email']]);
            return $this->createNewToken($token);
        } else {
            Log::error('Error en el proceso de signup');
            return response()->json(['error' => 'Error en el proceso de signup'], 401);
        }
    }

    public function logout() {
        Log::info('Cerrar Sesión');
        Auth::logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    protected function createNewToken($token){
        Log::info('Creando nuevo Token');
        Log::debug('Token: '.$token);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }
}
