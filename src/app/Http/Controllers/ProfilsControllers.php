<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProfilsControllers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all() {

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $Profils = \App\Profil::all();

        return $Profils;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $Profils = new \App\Profil;

        $Profils->nom = $request->input('nom');
        $Profils->prenom = $request->input('prenom');
        $Profils->telephone = $request->input('telephone');
        $Profils->gender = $request->input('gender');
        $Profils->date_naissance = $request->input('date_naissance');
        $Profils->adresse = $request->input('adresse');
        $Profils->code_postale = $request->input('code_postale');
        $Profils->fcm_token = $request->input('fcm_token');

        $Profil = $Profils->save();

        $cover = $request->file('bookcover');

        if( $cover ) {
            $extension = $cover->getClientOriginalExtension();
            Storage::disk('public')->put('Profil/Profil-'.$Profils->id.'.'.$extension,  File::get($cover));
            $Profils->image = 'Profil-'.$Profils->id;
            $Profils->update();
        }

        return response()->json(array('success' => true, 'id' => $Profils->id), 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $Profils = new \App\Profil;

        $Profil = $Profils::find($id);

        if( $Profil ) {

            return $Profil;

        }else {

            return response()->json("introuvable");

        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $Profils = new \App\Profil;

        $ProfilF = $Profils::find($id);

        if( $ProfilF ) {

            $ProfilF->nom = $request->input('nom');
            $ProfilF->prenom = $request->input('prenom');
            $ProfilF->telephone = $request->input('telephone');
            $ProfilF->gender = $request->input('gender');
            $ProfilF->date_naissance = $request->input('date_naissance');
            $ProfilF->adresse = $request->input('adresse');
            $ProfilF->code_postale = $request->input('code_postale');
            $ProfilF->fcm_token = $request->input('fcm_token');

            $ProfilF->save();

            $cover = $request->file('bookcover');

            if( $cover ) {
                $extension = $cover->getClientOriginalExtension();
                Storage::disk('public')->put('Profil/Profil'.$ProfilF->id.'.'.$extension,  File::get($cover));
                $ProfilF->image = 'Profil-'.$ProfilF->id;
                $ProfilF->save();
            }

            return response()->json(array('success' => true, 'id' => $ProfilF->id), 200);

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {

        try {
            $user = auth()->userOrFail();
        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        $Profils = new \App\Profil;

        $Result = false;

        $Profil = $Profils::find($id);

        if($Profil) {
            $Result = $Profil->delete();
        }

        return response()->json($Result);

    }

    
}
