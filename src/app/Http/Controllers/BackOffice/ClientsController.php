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

            $Profil = new \App\profil;
            $Profil->gender = $request->input('gender');
            $Profil->nom = $request->input('nom');
            $Profil->prenom = $request->input('prenom');
            $Profil->image = 'Profil-0.jpg';
            $Profil->save();

            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'status' => $request->status,
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


    public function show($id) {

        $ID_Boutique = $this->guard()->user()->boutique_id;

        if($id) {

           $Utilisateurs = DB::table('utilisateurs')
            ->join('profils', 'profils.id', '=', 'utilisateurs.profil_id')
            ->where('utilisateurs.role_id', '=', 3)
            ->where('utilisateurs.id', '=', $id)
            ->where('utilisateurs.boutique_id', '=', $ID_Boutique)
            ->select('utilisateurs.email', 'utilisateurs.status', 'profils.*')
            ->first();

            if($Utilisateurs) {
                return response()->json($Utilisateurs, 200);
            }else {
                return response()->json(false, 404);
            }

        }

    }

    public function update(Request $request, $id) {

        $ID_Boutique = $this->guard()->user()->boutique_id;

        if($ID_Boutique) {

            $Utilisateurs = new \App\utilisateur;
            $Utilisateur = $Utilisateurs::find($id);
            $Utilisateur->email = $request->input('email');
            $Utilisateur->status = $request->input('status');
            $Utilisateur->update();

            DB::table('profils')
            ->where('profils.id', $Utilisateur->profil_id)
            ->update([
                'nom' => $request->input('nom'),
                'prenom' => $request->input('prenom'),
                'gender' => $request->input('gender')
            ]);

            return response()->json(array('id' => $id), 200);

        }

    }

    public function delete($id) {

        $Utilisateurs = new \App\utilisateur;

        $Utilisateur = $Utilisateurs::find($id);

        $Result = false;

        if($Utilisateur) {

            $Result = $Utilisateur->delete();

        }

        return response()->json($Result);

    }

    public function guard() {

        return Auth::guard();

    }

}
