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

	public function allUser() {
		
		$Utilisateurs = DB::table('utilisateurs')
		->join('profils', 'profils.id', '=', 'utilisateurs.profil_id')
		->select('utilisateurs.email', 'profils.*')
		->get();

		return $Utilisateurs;

	}

	public function addUser(Request $request) {

		$Profil = new \App\Profil;
		$Profil->nom = $request->input('nom');
		$Profil->prenom = $request->input('prenom');
		$Profil->telephone = $request->input('telephone');
		$Profil->image = 'Profil-0.jpg';
		$Profil->save();

		$Boutique = new \App\Boutique;
		$Boutique->save();

		$user = User::create([
			'email' => $request->email,
			'password' => bcrypt($request->password),
			'status' => true,
			'role_id' => $request->role_id,
			'profil_id' => $Profil->id,
			'boutique_id' => $Boutique->id,
		]);

		return response()->json(array('id' => $user->id), 200);

	}

	public function guard() {

		return Auth::guard();

	}
   
}
