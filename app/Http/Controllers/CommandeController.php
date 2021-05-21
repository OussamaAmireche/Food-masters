<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\CommandePlat;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
            'date' => 'required|date_format:Y-m-d H:i:s',
            'email_client' => 'required|string',
            'id_restaurant' => 'required|integer',
            'state' => 'required|string',
            'payment_method' => 'required|string',
            'delivery_adress' => 'required|string',
            'plat_id' => 'required|integer',
            'quantité' => 'required|integer',
        ]);

        $commande = Commande::create([
            'date' => $fields['date'],
            'email_client' => $fields['email_client'],
            'state' => $fields['state'],
            'payment_method' => $fields['payment_method'],
            'id_restaurant' => $fields['id_restaurant'],
            'delivery_adress' => $fields['delivery_adress'],
        ]);

        $fields['commande_id'] = $commande->id;

        $commande_plat = CommandePlat::create([
            'plat_id' => $fields['plat_id'],
            'quantité' => $fields['quantité'],
            'commande_id' => $fields['commande_id'],
        ]);




        return response([$commande, $commande_plat], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }
}
