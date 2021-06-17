<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RatingRestaurant;

class RatingRestaurantController extends Controller
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
            'id_restaurant' => 'required|integer',
            'rating' => 'required|numeric'
        ]);

        $rating_restaurant = RatingRestaurant::create([
            'email_client' => $fields['email_client'],
            'id_restaurant' => $fields['id_restaurant'],
            'rating' => $fields['rating'],
        ]);

        return response($rating_restaurant, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return RatingRestaurant::where('id_restaurant', $id)->avg('rating');
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
        $rating_restaurant = RatingRestaurant::find($id);
        $rating_restaurant->update($request->all());
        return $rating_restaurant;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return RatingRestaurant::destroy($id);
    }
}
