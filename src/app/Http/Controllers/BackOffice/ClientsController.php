<?php

namespace App\Http\Controllers\BackOffice;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use DB;
use Illuminate\Support\Facades\Validator;

class ClientsController extends Controller {

    public function __construct() {

        $this->middleware('jwt', ['except' => ['signup']]);
    }

    public function store(Request $request) {

        $id = $this->guard()->user()->boutique_id;

        $validator = Validator::make($request->all(), [
            'email' => [ 'required', 'string', 'email', 'max:255', 'unique:utilisateurs,email'],
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->getMessages(), 400);
        }

        if($id) {

            $Profil = new \App\Profil;
            $Profil->gender = $request->input('gender');
            $Profil->nom = $request->input('nom');
            $Profil->prenom = $request->input('prenom');
            $Profil->image = 'Profil-0.jpg';
            $Profil->save();

            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'status' => true,
                'role_id' => 3,
                'profil_id' => $Profil->id,
                'boutique_id' => $id
            ]);

            return response()->json(array('id' => $user->id), 200);

        }

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
