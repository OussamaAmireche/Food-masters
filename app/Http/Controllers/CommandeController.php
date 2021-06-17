<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\CommandePlat;
use DB;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        return Commande::Join('commande_plat', 'commande.id', '=' , 'commande_plat.commande_id')
                        ->Join('clients', 'commande.email_client', '=', 'clients.email')
                        ->Join('plats', 'commande_plat.plat_id', '=', 'plats.id')
                        ->where('commande.id_restaurant', $id)
                        ->where('state', 'en attente')
                        ->select('commande.id', 'plats.name', 'commande_plat.quantité', 'commande.longitude', 'commande.latitude', 'commande.date', DB::raw('(plats.price * commande_plat.quantité) AS price'), 'clients.firstname', 'clients.lastname', 'commande.adress')
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
            'date' => 'required|date_format:Y-m-d H:i:s',
            'email_client' => 'required|string',
            'id_restaurant' => 'required|integer',
            'payment_method' => 'required|string',
            'longitude' => 'required',
            'latitude' => 'required',
            'adress' => 'required',
            'plat_id' => 'required|integer',
            'quantité' => 'required|integer',
        ]);

        $commande = Commande::create([
            'date' => $fields['date'],
            'email_client' => $fields['email_client'],
            'state' => 'en attente',
            'payment_method' => $fields['payment_method'],
            'id_restaurant' => $fields['id_restaurant'],
            'longitude' => $fields['longitude'],
            'latitude' => $fields['latitude'],
            'adress' => $fields['adress']
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
    public function show($email)
    {
        return Commande::Join('commande_plat', 'commande.id', '=' , 'commande_plat.commande_id')
                        ->Join('plats', 'plats.id', '=', 'commande_plat.plat_id')
                        ->Join('restaurants', 'restaurants.id', '=', 'commande.id_restaurant')
                        ->where('email_client', $email)
                        ->where('state', '<>', 'en attente')
                        ->select('commande.id AS commande_id', 'restaurants.name AS name_restaurant', 'state', 'quantité', 'date', 'plats.name AS name_plat', 'plats.picture AS picture_plat')
                        ->get();
    }

    public function en_attente($email)
    {
        return Commande::Join('commande_plat', 'commande.id', '=' , 'commande_plat.commande_id')
                        ->Join('plats', 'plats.id', '=', 'commande_plat.plat_id')
                        ->Join('restaurants', 'restaurants.id', '=', 'commande.id_restaurant')
                        ->where('email_client', $email)
                        ->where('state', 'en attente')
                        ->select('commande.id AS commande_id', 'restaurants.name AS name_restaurant', 'state', 'quantité', 'date', 'plats.name AS name_plat', 'plats.picture AS picture_plat')
                        ->get();
    }

    public function en_attente_gestionnaire($id)
    {
        return Commande::Join('commande_plat', 'commande.id', '=' , 'commande_plat.commande_id')
                        ->Join('plats', 'plats.id', '=', 'commande_plat.plat_id')
                        ->Join('restaurants', 'restaurants.id', '=', 'commande.id_restaurant')
                        ->Join('clients', 'commande.email_client', '=', 'clients.email')
                        ->where('commande.id_restaurant', $id)
                        ->where('state', 'en attente')
                        ->select('commande.longitude', 'commande.latitude', 'firstname', 'lastname', 'quantité', 'date', 'plats.name AS name_plat', 'price')
                        ->get();
    }

    public function show_gestionnaire($id)
    {
        return Commande::Join('commande_plat', 'commande.id', '=' , 'commande_plat.commande_id')
                        ->Join('plats', 'plats.id', '=', 'commande_plat.plat_id')
                        ->Join('restaurants', 'restaurants.id', '=', 'commande.id_restaurant')
                        ->Join('clients', 'commande.email_client', '=', 'clients.email')
                        ->where('commande.id_restaurant', $id)
                        ->where('state', '<>', 'en attente')
                        ->select('delivery_adress', 'firstname', 'lastname', 'quantité', 'date', 'plats.name AS name_plat', 'price', 'state')
                        ->get();
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function accept($id)
    {
        $commande = Commande::where('id', $id)->update(array('state' => 'accepté'));
        return $commande;
    }

    public function refuser($id)
    {
        $commande = Commande::where('id', $id)->update(array('state' => 'refusé'));
        return $commande;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function annuler($id)
    {
        $commande = Commande::where('id', $id)->update(array('state' => 'annulé'));
        return $commande;
    }

}
