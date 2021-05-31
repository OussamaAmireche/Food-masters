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
    public function index($id)
    {
        return Commande::Join('commande_plat', 'commande.id', '=' , 'commande_plat.commande_id')
                        ->where('id_restaurant', $id)
                        ->where('state', 'en attente')
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
    public function show($email)
    {
        return Commande::Join('commande_plat', 'commande.id', '=' , 'commande_plat.commande_id')
                        ->Join('plats', 'plats.id', '=', 'commande_plat.plat_id')
                        ->Join('restaurants', 'restaurants.id', '=', 'commande.id_restaurant')
                        ->where('email_client', $email)
                        ->where('state', '<>', 'en attente')
                        ->select('restaurants.name AS name_restaurant', 'state', 'quantité', 'date', 'plats.name AS name_plat', 'plats.picture AS picture_plat')
                        ->get();
    }

    public function en_attente($email)
    {
        return Commande::Join('commande_plat', 'commande.id', '=' , 'commande_plat.commande_id')
                        ->Join('plats', 'plats.id', '=', 'commande_plat.plat_id')
                        ->Join('restaurants', 'restaurants.id', '=', 'commande.id_restaurant')
                        ->where('email_client', $email)
                        ->where('state', 'en attente')
                        ->select('restaurants.name AS name_restaurant', 'state', 'quantité', 'date', 'plats.name AS name_plat', 'plats.picture AS picture_plat')
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
    public function destroy($id)
    {
        $commande_plat_annulé = CommandePlat::where('commande_id', $id)->delete();
        $commande_annulé = Commande::destroy($id);
        return [$commande_plat_annulé, $commande_annulé];
    }
}
