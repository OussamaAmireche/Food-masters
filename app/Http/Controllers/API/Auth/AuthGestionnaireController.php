<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\Gestionnaire;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class AuthGestionnaireController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string|unique:gestionnaires,email',
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'phone' => 'required|int|unique:gestionnaires,phone',
            'password' => 'required|string|confirmed',
        ]);

        $gestionnaire = Gestionnaire::create([
            'firstname' => $fields['firstname'],
            'lastname' => $fields['lastname'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'phone' => $fields['phone'],
        ]);

        $token = $gestionnaire->createToken('gestionnairetoken', ['role:gestionnaire'])->plainTextToken;

        $response = [
            'gestionnaire' => $gestionnaire,
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
        $gestionnaire = Gestionnaire::where('email', $fields['email'])->first();

        // Check password
        if(!$gestionnaire || !Hash::check($fields['password'], $gestionnaire->password)) {
            return response([
                'message' => 'informations incorrectes'
            ], 401);
        }

        $token = $gestionnaire->createToken('gestionnairetoken', ['role:gestionnaire'])->plainTextToken;

        $response = [
            'gestionnaire' => $gestionnaire,
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
}