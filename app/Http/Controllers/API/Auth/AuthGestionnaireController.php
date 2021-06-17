<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\Gestionnaire;
use App\Models\Client;
use DB;
use App\Models\Restaurant;
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
                'gestionnairephone' => 'required|int|unique:gestionnaires,phone',
                'password' => 'required|string|confirmed',
                'name' => 'required|string',
                'restaurantphone' => 'required|integer|unique:restaurants,phone',
                'adress' => 'required|string',
                'workhours' => 'required|string',
            ]);

            if ($request->hasFile('file')) {

                $request->validate([
                    'image' => 'mimes:jpeg,bmp,png,jpg' 
                ]);

                $request->file->store('images', 'public');

                $fields['picture'] = $request->file->hashName();
            
            }

        
        DB::transaction(function () use ($fields) {

            $gestionnaire = Gestionnaire::create([
                'firstname' => $fields['firstname'],
                'lastname' => $fields['lastname'],
                'email' => $fields['email'],
                'password' => Hash::make($fields['password']),
                'phone' => $fields['gestionnairephone'],
            ]);

            $token = $gestionnaire->createToken('gestionnairetoken', ['role:gestionnaire'])->plainTextToken;

            $response = [
                'gestionnaire' => $gestionnaire,
                'token' => $token,
            ];

            $restaurant = Restaurant::create([
                'name' => $fields['name'],
                //'picture' => $fields['picture'],
                'email_gestionnaire' => $fields['email'],
                'adress' => $fields['adress'],
                'phone' => $fields['restaurantphone'],
                'workhours' => $fields['workhours'],
            ]);

            return response([$response, $restaurant], 201);

        });
        
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

        $client = Client::all()->first();
        $token = $client->createToken('clienttoken', ['role:client'])->plainTextToken;
        // $token = $gestionnaire->createToken('clienttoken', ['role:client'])->plainTextToken;

        $gestionnaires = Gestionnaire::join('restaurants', 'restaurants.email_gestionnaire', '=', 'gestionnaires.email')
                                    ->where('email', $gestionnaire->email)
                                    ->select('email', 'firstname', 'lastname', 'gestionnaires.phone', 'restaurants.id')
                                    ->get();
        $response = [
            'gestionnaire' => $gestionnaires,
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
            'phone' => 'required|int',
            'password' => 'required|string',
            'oldpassword' => 'required|string',
        ]);

        // Check email
        $gestionnaire = Gestionnaire::where('email', $email)->first();

        // Check password
        if(!$gestionnaire || !Hash::check($fields['oldpassword'], $gestionnaire->password)) {
            return response([
                'message' => 'informations incorrectes'
            ], 401);
        }

        $gestionnaire = Gestionnaire::where('email', $email)->update(array(
            'firstname' => $fields['firstname'],
            'lastname' => $fields['lastname'],
            'phone' => $fields['phone'],
            'password' =>  Hash::make($fields['password']),
        ));
        return $gestionnaire;
    }
    public function show($email)
    {
        $gestionnaire = Gestionnaire::where('email', $email)
                        ->select('firstname', 'lastname', 'phone', 'email')
                        ->get();
        return $gestionnaire;
    }
}