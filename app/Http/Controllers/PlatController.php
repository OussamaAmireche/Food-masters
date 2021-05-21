<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plat;
use App\Models\RatingPlat;

class PlatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($name)
    {
        return Plat::leftJoin('rating_plat', 'plats.id', '=', 'rating_plat.id_plat')
                    ->groupBy('id_plat', 'name', 'ingredients', 'name_categorie', 'picture', 'price', 'id_restaurant')
                    ->where('name_categorie', $name)
                    ->select('name', 'ingredients', 'name_categorie', 'picture', 'price', 'id_restaurant', RatingPlat::raw('AVG(rating)'))
                    ->get(); 
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
            'name' => 'required|string',
            'price' => 'required|numeric',
            'id_restaurant' => 'required|integer',
            'picture' => 'required|string',
            'ingredients' => 'string',
            'name_categorie' => 'required|string',
        ]);

        $plat = Plat::create([
            'name' => $fields['name'],
            'picture' => $fields['picture'],
            'name_categorie' => $fields['name_categorie'],
            'price' => $fields['price'],
            'id_restaurant' => $fields['id_restaurant'],
            'ingredients' => $fields['ingredients'],
        ]);

        return response($plat, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Plat::where('id', $id)->get();
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
        $plat = Plat::find($id);
        $plat->update($request->all());
        return $plat;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Plat::destroy($id);
    }

    public function search($name)
    {
        return Plat::where('name', 'like', '%'.$name.'%')->get();
    }
}
