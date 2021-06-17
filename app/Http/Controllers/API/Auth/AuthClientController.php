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
            'phone' => 'required|integer|unique:clients,phone',
            'password' => 'required|string|confirmed',
            'longitude' => 'required',
            'latitude' => 'required',
            'delivery_adress' => 'required',
        ]);

        $client = Client::create([
            'firstname' => $fields['firstname'],
            'lastname' => $fields['lastname'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
            'phone' => $fields['phone'],
            'longitude' => $fields['longitude'],
            'latitude' => $fields['latitude'],
            'delivery_adress' => $fields['delivery_adress'],
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
        $fields = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'phone' => 'required|integer',
            'password' => 'required|string',
            'oldpassword' => 'required|string',
        ]);

        // Check email
        $client = Client::where('email', $email)->first();

        // Check password
        if(!$client || !Hash::check($fields['oldpassword'], $client->password)) {
            return response([
                'message' => 'informations incorrectes'
            ], 401);
        }

        $client = Client::where('email', $email)->update(array(
            'firstname' => $fields['firstname'],
            'lastname' => $fields['lastname'],
            'phone' => $fields['phone'],
            'password' =>  Hash::make($fields['password']),
        ));
        return $client;
    }

    public function show($email)
    {
        $client = Client::where('email', $email)
                        ->select('firstname', 'lastname', 'phone', 'email')
                        ->get();
        return $client;
    }

}
