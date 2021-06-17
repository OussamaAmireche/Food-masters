<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RatingPlat;

class RatingPlatController extends Controller
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
            'rating' => 'required|numeric'
        ]);

        $rating_plat = RatingPlat::create([
            'email_client' => $fields['email_client'],
            'id_plat' => $fields['id_plat'],
            'rating' => $fields['rating'],
        ]);

        return response($rating_plat, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return RatingPlat::where('id_plat', $id)->avg('rating');
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
        $rating_plat = RatingPlat::find($id);
        $rating_plat->update($request->all());
        return $rating_plat;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return RatingPlat::destroy($id);
    }
}
