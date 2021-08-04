<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\API\BaseController;
use Validator;
use Auth;
use Hash;

class UserController extends BaseController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return $this->responseError('Login failed', 422, $validator->errors());
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            
            $response = [
                'token' => $user->createToken('MyToken')->accessToken,
                'name' => $user->name,
            ];

            return $this->responseOk($response);
        } else {
            return $this->responseError('Wrong email or password', 401);
        }
    }

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed'],
            'role_id' => ['required', 'numeric'],
            'school_id' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return $this->responseError('Registration failed', 422, $validator->errors());
        }

        $params = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
            'school_id' => $request->school_id,
        ];

        if ($user = User::create($params)) {
            $token = $user->createToken('MyToken')->accessToken;

            $response = [
                'token' => $token,
                'user' => $user,
            ];

            return $this->responseOk($response);
        } else {
            return $this->responseError('Registration failed', 400);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return $this->responseOk(null, 200, 'Logged out successfully.');
    }

    public function ListUser()
    {
        $user = User::all();
        return $this->responseOk($user);
    }

    public function ListTeacher()
    {
        $user = User::select('name', 'email')->where('role_id', 2)->get();
        return $this->responseOk($user);
    }

     public function ListStudent()
    {
        $user = User::select('name', 'email')->where('role_id', 3)->get();
        return $this->responseOk($user);
    }
}
