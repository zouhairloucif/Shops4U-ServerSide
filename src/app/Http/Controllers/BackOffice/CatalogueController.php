<?php

namespace App\Http\Controllers\BackOffice;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use DB;

class CatalogueController extends Controller {

    public function __construct() {

        $this->middleware('jwt');

    }

    /* Code Produits */

    public function AllProduits() {


        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Produits = DB::table('produits')
            ->where('produits.boutique_id', '=', $id)
            ->get();

            return $Produits;

        }

    }

    public function StoreProduit(Request $request) {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Produit = new \App\Produit;
            $Produit->nom = $request->input('nom');
            $Produit->description = $request->input('description');
            $Produit->status = $request->input('status');
            $Produit->boutique_id = $id;
            $Produit->save();

            return response()->json(array('id' => $Produit->id), 200);

        }

    }

    /* Code Category */

    public function AllCategory() {


        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Categories = DB::table('categories')
            ->where('categories.boutique_id', '=', $id)
            ->get();

            return $Categories;

        }

    }

    public function showCategory($id) {

        $Categorys = new \App\Categorie;

        $Category = $Categorys::find($id);

        if( $Category ) {
            return $Category;
        }else {
            return response()->json(false);
        }

    }

    public function StoreCategory(Request $request) {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $NewCategory = new \App\Categorie;
            $NewCategory->nom = $request->input('nom');
            $NewCategory->description = $request->input('description');
            $NewCategory->status = $request->input('status');
            $NewCategory->parent = $request->input('parent');
            $NewCategory->boutique_id = $id;
            $ReCategory = $NewCategory->save();

            $image = $request->file('image');

            if( $image ) {
                $extension = $image->getClientOriginalExtension();
                Storage::disk('public')->put('Category/Category-'.$NewCategory->id.'.'.$extension,  File::get($image));
                $NewCategory->image = 'Category-'.$NewCategory->id.'.'.$extension;
                $NewCategory->update();
            }

            return response()->json(array('id' => $NewCategory->id), 200);

        }

    }

    public function UpdateCategory(Request $request, $id) {

        $Categorys = new \App\Categorie;

        $Category = $Categorys::find($id);

        if($Category) {

            $Category->nom = $request->input('nom');
            $Category->description = $request->input('description');
            $Category->utilisateur_id = $request->input('utilisateur_id');
            $Category->parent = $request->input('parent');
            $Category->update();

            $image = $request->file('image');

            if( $image ) {
                $extension = $image->getClientOriginalExtension();
                Storage::disk('public')->put('Category/Category-'.$Category->id.'.'.$extension,  File::get($image));
                $Category->image = 'Category-'.$Category->id.'.'.$extension;
                $Category->update();
            }

            return response()->json(array('id' => $Category->id), 200);

        }

    }

    public function DestroyCategory($id) {

        $Categorys = new \App\Categorie;
        $Category = $Categorys::find($id);
        $Result = false;

        if($Category) {

            $Result = $Category->delete();
        }

        return response()->json($Result);

    }

    /* Code Reduction */

    public function AllReduction() {

        $reductions = \App\Reduction::all();

        return $reductions;

    }

    public function showReduction($id) {

        $Reductions = new \App\Reduction;

        $Reduction = $Reductions::find($id);

        if($Reduction) {
            return $Reduction;
        }else {
            return response()->json(false);
        }

    }

    public function StoreReduction(Request $request) {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Reduction = new \App\Reduction;
            $Reduction->nom = $request->input('nom');
            $Reduction->description = $request->input('description');
            $Reduction->code = $request->input('code');
            $Reduction->value = $request->input('value');
            $Reduction->quantite = $request->input('quantite');
            $Reduction->valide = $request->input('valide');
            $Reduction->status = $request->input('status');
            $Reduction->boutique_id = $id;
            $Reduction->save();

            return response()->json(array('id' => $Reduction->id), 200);

        }

    }

    public function UpdateReduction(Request $request, $id) {

        $Reductions = new \App\Reduction;

        $Reduction = $Reductions::find($id);

        if($Reduction) {

            $Reduction->nom = $request->input('nom');
            $Reduction->description = $request->input('description');
            $Reduction->code = $request->input('code');
            $Reduction->quantite = $request->input('quantite');
            $Reduction->valide = $request->input('valide');
            $Reduction->status = $request->input('status');
            $Reduction->boutique_id = $request->input('boutique_id');
            $Reduction->update();

            return response()->json(array('id' => $Reduction->id), 200);

        }

    }

    public function DestroyReduction($id) {

        $reductions = new \App\Reduction;

        $Reduction = $reductions::find($id);

        $Result = false;

        if($Reduction) {

            $Result = $Reduction->delete();
        }

        return response()->json($Result);

    }

    /* Code Fournisseur */

    public function AllFournisseur() {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Fournisseurs = DB::table('fournisseurs')
            ->where('fournisseurs.boutique_id', '=', $id)
            ->get();

            return $Fournisseurs;

        }

    }

    public function showFournisseur($id) {

        $Fournisseurs = new \App\Fournisseur;

        $Fournisseur = $Fournisseurs::find($id);

        if($Fournisseur) {
            return $Fournisseur;
        }else {
            return response()->json(false);
        }

    }

    public function StoreFournisseur(Request $request) {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Fournisseur = new \App\Fournisseur;

            $Fournisseur->nom = $request->input('nom');
            $Fournisseur->telephone = $request->input('telephone');
            $Fournisseur->email = $request->input('email');
            $Fournisseur->adresse = $request->input('adresse');
            $Fournisseur->status = $request->input('status');
            $Fournisseur->boutique_id = $id;
            $Fournisseur->save();

            return response()->json(array('id' => $Fournisseur->id), 200);

        }

    }

    public function UpdateFournisseur(Request $request, $id) {

        $Fournisseurs = new \App\Fournisseur;

        $Fournisseur = $Fournisseurs::find($id);

        if($Fournisseur) {

            $Fournisseur->nom = $request->input('nom');
            $Fournisseur->telephone = $request->input('telephone');
            $Fournisseur->email = $request->input('email');
            $Fournisseur->adresse = $request->input('adresse');
            $Fournisseur->boutique_id = $request->input('boutique_id');
            $Fournisseur->update();

            return response()->json(array('id' => $Fournisseur->id), 200);

        }

    }

    public function DestroyFournisseur($id) {

        $Fournisseurs = new \App\Fournisseur;

        $Fournisseur = $Fournisseurs::find($id);

        $Result = false;

        if($Fournisseur) {

            $Result = $Fournisseur->delete();
        }

        return response()->json($Result);

    }


    /* Code Marque */

    public function AllMarque() {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Marques = DB::table('marques')
            ->where('marques.boutique_id', '=', $id)
            ->get();

            for ($i=0; $i < count($Marques) ; $i++) { 
                if($Marques[$i]->image){
                    $Marques[$i]->image = "http://localhost/Shops4U/src/storage/app/public/Marque/".$Marques[$i]->image;
                }
            }

            return $Marques;

        }

    }

    public function showMarque($id) {

        $Marques = new \App\Marque;

        $Marque = $Marques::find($id);

        if($Marque) {
            return $Marque;
        }else {
            return response()->json(false);
        }

    }

    public function StoreMarque(Request $request) {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Marque = new \App\Marque;

            $Marque->nom = $request->input('nom');
            $Marque->description = $request->input('description');
            $Marque->status = $request->input('status');
            $Marque->boutique_id = $id;
            $Marque->save();

            $image = $request->file('image');

            if( $image ) {
                $extension = $image->getClientOriginalExtension();
                Storage::disk('public')->put('Marque/Marque-'.$Marque->id.'.'.$extension,  File::get($image));
                $Marque->image = 'Marque-'.$Marque->id.'.'.$extension;
                $Marque->update();
            }

            return response()->json(array('id' => $Marque->id), 200);

        }

    }

    public function UpdateMarque(Request $request, $id) {

        $Marques = new \App\Marque;

        $Marque = $Marques::find($id);

        if($Marque) {

            $Marque->nom = $request->input('nom');
            $Marque->description = $request->input('description');
            $Marque->boutique_id = $request->input('boutique_id');
            $Marque->update();

            $image = $request->file('image');

            if( $image ) {
                $extension = $image->getClientOriginalExtension();
                Storage::disk('public')->put('Marque/Marque-'.$Marque->id.'.'.$extension,  File::get($image));
                $Marque->image = 'Marque-'.$Marque->id.'.'.$extension;
                $Marque->update();
            }

            return response()->json(array('id' => $Marque->id), 200);

        }

    }

    public function DestroyMarque($id) {

        $Marques = new \App\Marque;

        $Marque = $Marques::find($id);

        $Result = false;

        if($Marque) {

            $Result = $Marque->delete();
        }

        return response()->json($Result);

    }

    public function guard() {

        return Auth::guard();

    }

}
