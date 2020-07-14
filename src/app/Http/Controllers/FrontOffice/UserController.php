<?php

namespace App\Http\Controllers\FrontOffice;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {

    public function __construct() {

        $this->middleware('jwt', ['except' => ['signup']]);

    }

    /* Admin */

    public function all() {

        $ID_Boutique = $this->guard()->user()->boutique_id;
        $id = $this->guard()->user()->id;
        $user = DB::table('utilisateurs')
            ->join('profils', 'profils.id', '=', 'utilisateurs.profil_id')
            ->where('utilisateurs.id', '!=', $id)
            ->where('utilisateurs.boutique_id', '=', $ID_Boutique)
            ->where('utilisateurs.role_id', '=', 2)
            ->select('utilisateurs.email', 'utilisateurs.status', 'profils.*')
            ->get();

        return response()->json($user);

    }

    public function show($id) {

        $user = DB::table('utilisateurs')
            ->join('profils', 'profils.id', '=', 'utilisateurs.profil_id')
            ->where('utilisateurs.id', '=', $id)
            ->select('utilisateurs.email', 'utilisateurs.status', 'profils.*')
            ->first();

        $user->image = "http://localhost/Shops4U/src/storage/app/public/Profil/".$user->image;

        return response()->json($user);

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
                'role_id' => 2,
                'profil_id' => $Profil->id,
                'boutique_id' => $id
            ]);

            return response()->json(array('id' => $user->id), 200);

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

    public function showActive() {

        $id = $this->guard()->user()->id;

        $user = DB::table('utilisateurs')
            ->join('profils', 'profils.id', '=', 'utilisateurs.profil_id')
            ->where('utilisateurs.id', '=', $id)
            ->select('utilisateurs.email', 'profils.*')
            ->first();

        $user->image = "http://localhost/Shops4U/src/storage/app/public/Profil/".$user->image;

        return response()->json($user);

    }

    public function signup(Request $request) {

        $validator = Validator::make($request->all(), [
            'email' => [ 'required', 'string', 'email', 'max:255', 'unique:utilisateurs,email'],
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->getMessages(), 400);
        }

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
            'status' => true,
            'role_id' => $request->role_id,
            'profil_id' => $Profil->id,
            'boutique_id' => $Boutique->id,
        ]);

        $token = $this->guard()->login($user);

        return $this->respondWithToken($token);

    }

    public function UpdateActive(Request $request) {

        $user = auth()->user();

        $Profils = new \App\profil;

        $Profil = $Profils::find($user->profil_id);

        if($Profil) {
            $Profil->nom = $request->input('nom');
            $Profil->prenom = $request->input('prenom');
            $Profil->telephone = $request->input('telephone');
            $Profil->gender = $request->input('gender');
            $Profil->date_naissance = $request->input('date_naissance');
            $Profil->code_postale = $request->input('code_postale');
            $Profil->adresse = $request->input('adresse');
            $Profil->update();
        }

        $image = $request->file('image');

        if( $image ) {

            $extension = $image->getClientOriginalExtension();
            Storage::disk('public')->put('Profil/Profil-'.$Profil->id.'.'.$extension,  File::get($image));
            $Profil->image = 'Profil-'.$Profil->id.'.'.$extension;
            $Profil->update();
        }

        return response()->json(array('id' => $Profil->id), 200);

    }

    protected function respondWithToken($token) {

        $role_id = auth()->user()->role_id;
        $role = \DB::table('roles')->where('id', $role_id)->pluck('nom');
        $boutique = auth()->user()->boutique_id;
        $profil = auth()->user()->profil_id;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60,
            'role' => $role,
            'boutique' => $boutique,
            'profil' => $profil,
        ]);

    }


    public function guard() {

        return Auth::guard();

    }

}
