<?php

namespace App\Http\Controllers\BackOffice;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use DB;

class LivraisonController extends Controller {

    public function __construct() {

        $this->middleware('jwt', ['except' => ['signup']]);

    }

    /* Transporteur */

    public function allTransporteur() {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Transporteurs = DB::table('transporteurs')
            ->where('transporteurs.boutique_id', '=', $id)
            ->get();

            for ($i=0; $i < count($Transporteurs) ; $i++) { 
                if($Transporteurs[$i]->image){
                    $Transporteurs[$i]->image = "http://localhost/Shops4U/src/storage/app/public/Transporteur/".$Transporteurs[$i]->image;
                }
            }

            return $Transporteurs;

        }

    }

    public function storeTransporteur(Request $request) {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Transporteur = new \App\transporteur;
            $Transporteur->nom = $request->input('nom');
            $Transporteur->type = $request->input('type');
            $Transporteur->delai = $request->input('delai');
            $Transporteur->status = $request->input('status');
            $Transporteur->boutique_id = $id;
            $Transporteur->save();

            $image = $request->file('image');

            if( $image ) {
                $extension = $image->getClientOriginalExtension();
                Storage::disk('public')->put('Transporteur/Transporteur-'.$Transporteur->id.'.'.$extension,  File::get($image));
                $Transporteur->image = 'Transporteur-'.$Transporteur->id.'.'.$extension;
                $Transporteur->update();
            }

            $LivraisonJson = json_decode($request->get('Livraison'));

            if($LivraisonJson) {

                for ($i=0; $i < count($LivraisonJson) ; $i++) { 

                    $Livraison = new \App\livraison;
                    $Livraison->transporteur_id = $Transporteur->id;
                    $Livraison->prix = $LivraisonJson[$i]->val;

                    if("zone"==$request->input('type')) {
                        $Livraison->zone_id = $LivraisonJson[$i]->id;
                    }

                    if("pays"==$request->input('type')) {
                        $Livraison->pays_id = $LivraisonJson[$i]->id;
                    }

                    if("ville"==$request->input('type')) {
                        $Livraison->ville_id = $LivraisonJson[$i]->id;
                    }
                    
                    $Livraison->save();
               
                }

            }

            return response()->json(array('id' => $Transporteur->id), 200);

        }

    }

    public function DeleteTransporteur($id) {

        $Transporteurs = new \App\transporteur;

        $Transporteur = $Transporteurs::find($id);

        $Result = false;

        if($Transporteur) {

            $Result = $Transporteur->delete();

        }

        return response()->json($Result);

    }

    /* Zone */

    public function allZone() {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Zones = DB::table('zones')
            ->where('zones.boutique_id', '=', $id)
            ->get();

            return $Zones;

        }

    }

    public function storeZone(Request $request) {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Zone = new \App\zones;
            $Zone->zone = $request->input('zone');
            $Zone->status = $request->input('status');
            $Zone->boutique_id = $id;
            $Zone->save();

            return response()->json(array('id' => $Zone->id), 200);

        }

    }

    public function DeleteZone($id) {

        $Zones = new \App\zones;

        $Zone = $Zones::find($id);

        $Result = false;

        if($Zone) {

            $Result = $Zone->delete();

        }

        return response()->json($Result);

    }


    /* Ville */

    public function allVille() {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Ville = DB::table('villes')
            ->where('villes.boutique_id', '=', $id)
            ->get();

            return $Ville;

        }

    }

    public function storeVille(Request $request) {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Ville = new \App\ville;
            $Ville->ville = $request->input('ville');
            $Ville->status = $request->input('status');
            $Ville->boutique_id = $id;
            $Ville->pays_id = $request->input('pays_id');
            $Ville->save();

            return response()->json(array('id' => $Ville->id), 200);

        }

    }

    public function DeleteVille($id) {

        $Villes = new \App\ville;

        $Ville = $Villes::find($id);

        $Result = false;

        if($Ville) {

            $Result = $Ville->delete();

        }

        return response()->json($Result);

    }


    /* Pays */

    public function allPays() {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Pays = DB::table('pays')
            ->where('pays.boutique_id', '=', $id)
            ->get();

            return $Pays;

        }

    }

    public function storePays(Request $request) {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Pays = new \App\pays;
            $Pays->pays = $request->input('pays');
            $Pays->status = $request->input('status');
            $Pays->boutique_id = $id;
            $Pays->zone_id = $request->input('zone_id');
            $Pays->save();

            return response()->json(array('id' => $Pays->id), 200);

        }

    }

    public function DeletePays($id) {

        $Payss = new \App\pays;

        $Pays = $Payss::find($id);

        $Result = false;

        if($Pays) {

            $Result = $Pays->delete();

        }

        return response()->json($Result);

    }

    public function guard() {

        return Auth::guard();

    }

}
