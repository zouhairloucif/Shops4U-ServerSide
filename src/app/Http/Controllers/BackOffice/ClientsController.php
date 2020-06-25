<?php

namespace App\Http\Controllers\BackOffice;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use DB;

class ClientsController extends Controller {

    public function __construct() {

        $this->middleware('jwt', ['except' => ['signup']]);
    }

    public function all() {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Utilisateurs = DB::table('utilisateurs')
            ->join('profils', 'profils.id', '=', 'utilisateurs.profil_id')
            ->where('utilisateurs.boutique_id', '=', $id)
            ->where('utilisateurs.role_id', '=', 3)
            ->select('utilisateurs.email', 'utilisateurs.status', 'profils.*')
            ->get();

        }

        return $Utilisateurs;

    }

    public function guard() {

        return Auth::guard();

    }

}
