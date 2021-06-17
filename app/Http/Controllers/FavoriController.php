<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favori;
use App\Models\RatingPlat;

class FavoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'email_client' => 'required|string',
            'id_plat' => 'required|integer',
        ]);

        $favori = Favori::create([
            'email_client' => $fields['email_client'],
            'id_plat' => $fields['id_plat'],
        ]);

        return response($favori, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($email)
    {
        return Favori::where('favoris.email_client', $email)
                    ->join('plats', 'plats.id', '=', 'favoris.id_plat')
                    ->leftJoin('rating_plat', 'plats.id', '=', 'rating_plat.id_plat')
                    ->join('restaurants', 'restaurants.id', '=', 'plats.id_restaurant')
                    ->groupBy('favoris.id_plat', 'plats.name', 'plats.picture', 'restaurants.name', 'ingredients', 'price')
                    ->select('favoris.id_plat', 'plats.name AS name_plat', 'restaurants.name AS name_restaurant', 'price', 'plats.picture AS picture', 'ingredients', RatingPlat::raw('AVG(rating)'))
                    ->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Favori::destroy($id);
    }
}
