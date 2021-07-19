<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private $table = 'msuser';
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'username' => "required|string|unique:$this->table",
            'password' => 'required|confirmed',
        ]);

        try
        {
            $user = new Users();
            $user->username= $request->input('username');
            $user->userpassword = app('hash')->make($request->input('password'));
            $user->save();

            return response()->json( [
                'entity' => 'users',
                'action' => 'create',
                'result' => 'success'
            ], 201);

        }
        catch (Exception $e)
        {
            return response()->json( [
                'entity' => 'users',
                'action' => 'create',
                'result' => 'failed'
            ], 409);
        }
    }

    public function login(Request $request)
    {
        try {
            $this->customValidate($request->all(), array(
                'username:Nama pengguna' => 'required|string',
                'password:Kata sandi' => 'required|string',
            ));

            $credentials = $request->only(['username', 'password']);

            if (! $token = Auth::attempt($credentials)) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $response = \auth()->user();
            $response['token'] = $token;

            return $this->jsonSuccess(null, $response);
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function me()
    {
        try {
            return $this->jsonSuccess(null, \auth()->user());
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }
}
