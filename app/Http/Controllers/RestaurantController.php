<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\RatingRestaurant;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Restaurant::leftJoin('rating_restaurant', 'restaurants.id', '=', 'rating_restaurant.id_restaurant')
                    ->groupBy('id_restaurant', 'name', 'workhours', 'adress', 'picture', 'email_gestionnaire', 'phone')
                    ->select('name', 'workhours', 'adress', 'picture', 'email_gestionnaire', 'phone', RatingRestaurant::raw('AVG(rating)'))
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
            'phone' => 'required|integer|unique:restaurants,phone',
            'adress' => 'required|string',
            'picture' => 'string',
            'workhours' => 'required|string',
            'email_gestionnaire' => 'required|string|unique:restaurants,email_gestionnaire',
        ]);

        $restaurant = Restaurant::create([
            'name' => $fields['name'],
            'picture' => $fields['picture'],
            'email_gestionnaire' => $fields['email_gestionnaire'],
            'adress' => $fields['adress'],
            'phone' => $fields['phone'],
            'workhours' => $fields['workhours'],
        ]);

        return response($restaurant, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Restaurant::where('id', $id)->get();
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
        $restaurant = Restaurant::find($id);
        $restaurant->update($request->all());
        return $restaurant;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Restaurant::destroy($id);
    }

    public function search($name)
    {
        return Restaurant::where('name', 'like', '%'.$name.'%')->get();
    }
}
