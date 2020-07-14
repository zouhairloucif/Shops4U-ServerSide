<?php

namespace App\Http\Controllers\BackOffice;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use DB;

class SaUserController extends Controller {

    public function addSuperAdmin(Request $request) {

        $Profil = new \App\profil;
        $Profil->nom = $request->input('nom');
        $Profil->prenom = $request->input('prenom');
        $Profil->telephone =  $request->input;
        $Profil->image = 'Profil-0.jpg';
        $Profil->save();

        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' =>  $request->input('status'),
            'role_id' => 1,
            'profil_id' => $Profil->id,
            'boutique_id' => null,
        ]);

        return response()->json(array('id' => $user->id), 200);

    }

    public function addVendeur(Request $request) {

        $Profil = new \App\profil;
        $Profil->nom = $request->input('nom');
        $Profil->prenom = $request->input('prenom');
        $Profil->telephone = $request->input('telephone');
        $Profil->image = 'Profil-0.jpg';
        $Profil->save();
        $Boutique = new \App\boutique;
        $Boutique->save();

        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => $request->input('status'),
            'role_id' => 2,
            'profil_id' => $Profil->id,
            'boutique_id' => $Boutique->id,
        ]);

        return response()->json(array('id' => $user->id), 200);

    }

    public function addClient(Request $request) {

        $Profil = new \App\profil;
        $Profil->nom = $request->input('nom');
        $Profil->prenom = $request->input('prenom');
        $Profil->telephone = $request->input('telephone');
        $Profil->image = 'Profil-0.jpg';
        $Profil->save();

        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => $request->input('status'),
            'role_id' => 3,
            'profil_id' => $Profil->id,
            'boutique_id' => null,
        ]);

        return response()->json(array('id' => $user->id), 200);

    }

    public function showSuperAdmin() {

        $superAdmin = DB::table('utilisateurs')
            ->join('profils', 'profils.id', '=', 'utilisateurs.profil_id')
            ->join('roles', 'roles.id', '=', 'utilisateurs.role_id')
            ->where('utilisateurs.role_id', '=', 1)
            ->select('utilisateurs.status','utilisateurs.email', 'profils.*','roles.nom as role')
            ->get();

        return $superAdmin;

    }

    public function showVendeur() {

        $superAdmin = DB::table('utilisateurs')
            ->join('profils', 'profils.id', '=', 'utilisateurs.profil_id')
            ->join('roles', 'roles.id', '=', 'utilisateurs.role_id')
            ->where('utilisateurs.role_id', '=', 2)
            ->select('utilisateurs.status','utilisateurs.email', 'profils.*','roles.nom as role')
            ->get();

        return $superAdmin;

    }

    public function showClient() {

        $superAdmin = DB::table('utilisateurs')
            ->join('profils', 'profils.id', '=', 'utilisateurs.profil_id')
            ->join('roles', 'roles.id', '=', 'utilisateurs.role_id')
            ->where('utilisateurs.role_id', '=', 3)
            ->select('utilisateurs.status','utilisateurs.email', 'profils.*','roles.nom as role')
            ->get();

        return $superAdmin;

    }

    public function allSa() {

        $superAdmin = DB::table('utilisateurs')
            ->join('profils', 'profils.id', '=', 'utilisateurs.profil_id')
            ->join('roles', 'roles.id', '=', 'utilisateurs.role_id')
            ->where('utilisateurs.role_id', '=', 1)
            ->select('utilisateurs.email', 'profils.*','roles.nom as role')
            ->get();

        $allSa = count($superAdmin);

        return $allSa;

    }

    public function allV() {

        $vendeur = DB::table('utilisateurs')
            ->join('profils', 'profils.id', '=', 'utilisateurs.profil_id')
            ->join('roles', 'roles.id', '=', 'utilisateurs.role_id')
            ->where('utilisateurs.role_id', '=', 2)
            ->select('utilisateurs.email', 'profils.*','roles.nom as role')
            ->get();

        $allV = count($vendeur);

        return $allV;

    }

    public function allC() {

        $client = DB::table('utilisateurs')
            ->join('profils', 'profils.id', '=', 'utilisateurs.profil_id')
            ->join('roles', 'roles.id', '=', 'utilisateurs.role_id')
            ->where('utilisateurs.role_id', '=', 3)
            ->select('utilisateurs.email', 'profils.*','roles.nom as role')
            ->get();

        $allC = count($client);

        return $allC;

    }

    public function deleteUser($id) {

        $utilisateur = new \App\utilisateur;

        $utilisateur = $utilisateur::find($id);

        $Result = false;

        if($utilisateur) {

            $Result = $utilisateur->delete();

        }

        return response()->json($Result);

    }



    public function guard() {

        return Auth::guard();

    }

}
