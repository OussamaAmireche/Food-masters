<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;


class AuthClientController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string|unique:clients,email',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'phone' => 'required|int|unique:clients,phone',
            'password' => 'required|string|confirmed',
            'adress' => 'required'
        ]);

        $client = Client::create([
            'firstname' => $fields['firstname'],
            'lastname' => $fields['lastname'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
            'phone' => $fields['phone'],
            'adress' => $fields['adress']
        ]);

        $token = $client->createToken('clienttoken', ['role:client'])->plainTextToken;

        $response = [
            'client' => $client,
            'token' => $token,
        ];

        return response($response, 201);
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $client = Client::where('email', $fields['email'])->first();

        // Check password
        if(!$client || !Hash::check($fields['password'], $client->password)) {
            return response([
                'message' => 'informations incorrectes'
            ], 401);
        }

        $token = $client->createToken('clienttoken', ['role:client'])->plainTextToken;

        $response = [
            'client' => $client,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }

    public function update(Request $request, $email)
    {   
        $client = Client::where('email', $email)->update($request->all());
        return $client;
    }

}
