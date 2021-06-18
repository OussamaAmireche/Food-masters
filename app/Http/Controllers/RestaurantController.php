<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Plat;
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
                    ->groupBy('restaurants.id', 'name', 'workhours', 'adress', 'picture', 'email_gestionnaire', 'phone')
                    ->select('restaurants.id', 'name', 'workhours', 'adress', 'picture', 'email_gestionnaire', 'phone', RatingRestaurant::raw('ROUND(AVG(rating), 1) AS rating'))
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
            'workhours' => 'required|string',
            'email_gestionnaire' => 'required|string|unique:restaurants,email_gestionnaire',
        ]);

        if ($request->hasFile('file')) {

            $request->validate([
                'image' => 'mimes:jpeg,bmp,png,jpg' 
            ]);

            $request->file->store('images', 'public');

        $restaurant = Restaurant::create([
            'name' => $fields['name'],
            'picture' => $request->file->hashName(),
            'email_gestionnaire' => $fields['email_gestionnaire'],
            'adress' => $fields['adress'],
            'phone' => $fields['phone'],
            'workhours' => $fields['workhours'],
        ]);

        return response($restaurant, 201);

        }
        else{
            return response([
                'message' => 'format d\'image non reconnu'
            ], 401);
        } 
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Restaurant::leftJoin('rating_restaurant', 'restaurants.id', '=', 'rating_restaurant.id_restaurant')
                        ->groupBy('id_restaurant', 'name', 'workhours', 'adress', 'picture', 'email_gestionnaire', 'phone')
                        ->where('restaurants.id', $id)
                        ->select('id_restaurant', 'name', 'workhours', 'adress', 'picture', 'email_gestionnaire', 'phone', RatingRestaurant::raw('ROUND(AVG(rating), 1) AS rating'))
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

        $fields = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|integer',
            'adress' => 'required|string',
            'workhours' => 'required|string',
        ]);

        if ($request->hasFile('file')) {

            $request->validate([
                'image' => 'mimes:jpeg,bmp,png,jpg' 
            ]);

            $request->file->store('images', 'public');

            $restaurant = Restaurant::where('id', $id)->update(array(
                'picture' => $request->file->hashName(),
            ));
        
        }

            $restaurant = Restaurant::where('id', $id)->update(array(
                'name' => $fields['name'],
                'adress' => $fields['adress'],
                'phone' => $fields['phone'],
                'workhours' => $fields['workhours'],
            ));
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

    public function upload(Request $request, $id, $type){

        if($type == 'restaurant')
        {
            $has_file = $request->hasFile('file');
            if ($request->hasFile('file')) {

                $request->validate([
                    'image' => 'mimes:jpeg,bmp,png,jpg' 
                ]);
    
                $request->file->store('images', 'public');
    
                $restaurant = Restaurant::find($id);
                $restaurant->picture = $request->file->hashName();
                $restaurant->save();
        }
        }
        elseif($type == 'plat'){

            if ($request->hasFile('file')) {

                $request->validate([
                    'image' => 'mimes:jpeg,bmp,png,jpg' 
                ]);
    
                $request->file->store('images', 'public');

                $plat = Plat::find($id);        
                $plat->picture = $request->file->hashName();
                $plat->save();
            }
        }
        

}
}
