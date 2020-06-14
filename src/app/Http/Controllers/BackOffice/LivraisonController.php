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

    public function allZone() {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Zones = DB::table('zones')
            ->where('zones.boutique_id', '=', $id)
            ->get();

            return $Zones;

        }

    }

    public function allPays() {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Pays = DB::table('pays')
            ->where('pays.boutique_id', '=', $id)
            ->get();

            return $Pays;

        }

    }

    public function allVille() {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Ville = DB::table('villes')
            ->where('villes.boutique_id', '=', $id)
            ->get();

            return $Ville;

        }

    }

    public function storeZone(Request $request) {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Zone = new \App\Zones;
            $Zone->zone = $request->input('zone');
            $Zone->status = $request->input('status');
            $Zone->boutique_id = $id;
            $Zone->save();

            return response()->json(array('id' => $Zone->id), 200);

        }

    }

    public function storePays(Request $request) {
        
        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Pays = new \App\Pays;
            $Pays->pays = $request->input('pays');
            $Pays->status = $request->input('status');
            $Pays->boutique_id = $id;
            $Pays->zone_id = $request->input('zone_id');
            $Pays->save();

            return response()->json(array('id' => $Pays->id), 200);

        }

    }

    public function storeVille(Request $request) {
        
        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Ville = new \App\Ville;
            $Ville->ville = $request->input('ville');
            $Ville->status = $request->input('status');
            $Ville->boutique_id = $id;
            $Ville->pays_id = $request->input('pays_id');
            $Ville->save();

            return response()->json(array('id' => $Ville->id), 200);

        }

    }

    public function guard() {

        return Auth::guard();

    }

}
