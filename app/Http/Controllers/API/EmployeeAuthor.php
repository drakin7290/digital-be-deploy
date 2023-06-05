<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeAuthor extends BaseController
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'pass' => 'required|confirmed'
        ]);

        $validatedData['pass'] = bcrypt($request->pass);

        $user = Employee::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;
        $data['employee'] = $user;
        $data['access_token'] = $accessToken;

        return sendResponse($data, "Employee created successful");
    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'username' => 'email|required',
            'pass' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['message' => 'Invalid Credentials']);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        $data['employee'] = auth()->user();
        $data['access_token'] = $accessToken;

        return response($data, "Login successful!");

    }
}