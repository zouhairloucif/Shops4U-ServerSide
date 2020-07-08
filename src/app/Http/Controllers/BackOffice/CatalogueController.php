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
            ->join('stocks', 'stocks.id', '=', 'produits.stock_id')
            ->join('photos_produits', 'photos_produits.produit_id', '=', 'produits.id')
            ->where('produits.boutique_id', '=', $id)
            ->where('photos_produits.order', '=', '0')
            ->select('produits.*', 'stocks.quantite as quantite', 'photos_produits.src as image')
            ->get();

            for ($i=0; $i < count($Produits) ; $i++) { 
                if($Produits[$i]->image){
                    $Produits[$i]->image = "http://localhost/Shops4U/src/storage/app/public/Produit/".$Produits[$i]->image;
                }
            }

            return $Produits;

        }

    }

    public function StoreProduit(Request $request) {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Stock = new \App\stock;
            $Stock->quantite = $request->input('quantite');
            $Stock->save();

            $Produit = new \App\produit;
            $Produit->nom = $request->input('nom');
            $Produit->description = $request->input('description');
            $Produit->status = $request->input('status');
            $Produit->montant_HT = $request->input('montant_HT');
            $Produit->montant_TTC = $request->input('montant_TTC');
            $Produit->prix_final = $request->input('montant_TTC');
            $Produit->tva = $request->input('tva');
            $Produit->boutique_id = $id;
            $Produit->fournisseur_id = $request->input('fournisseur_id');
            $Produit->marque_id = $request->input('marque_id');
            $Produit->stock_id = $Stock->id;
            $Produit->save();

            $categories = json_decode($request->get('categories'));

            if($categories) {

                for ($i=0; $i < count($categories) ; $i++) { 

                    $CategoryProduit = new \App\categorie_produit;
                    $CategoryProduit->produit_id = $Produit->id;
                    $CategoryProduit->categorie_id = $categories[$i]->id;    
                    $CategoryProduit->save();

                }

            }

            $image = $request->file('image');

            if( $image ) {
                $extension = $image->getClientOriginalExtension();
                Storage::disk('public')->put('Produit/Produit-'.$Produit->id.'.'.$extension,  File::get($image));
                $PhotosPorduit = new \App\photos_produit;
                $PhotosPorduit->produit_id = $Produit->id;
                $PhotosPorduit->src = 'Produit-'.$Produit->id.'.'.$extension;
                $PhotosPorduit->order = 0;
                $PhotosPorduit->save();
            }else {
                $PhotosPorduit = new \App\photos_produit;
                $PhotosPorduit->produit_id = $Produit->id;
                $PhotosPorduit->src = 'Produit-0.png';
                $PhotosPorduit->order = 0;
                $PhotosPorduit->save();
            }

            return response()->json(array('id' => $Produit->id), 200);

        }

    }

    public function UpdateProduit(Request $request,$id) {

        $ID_Boutique = $this->guard()->user()->boutique_id;

        if($ID_Boutique) {

            DB::table('produits')
            ->where('produits.id', $id)
            ->where('produits.boutique_id', $ID_Boutique)
            ->update([
                'nom' => $request->input('nom'),
                'description' => $request->input('description'),
                'status' => $request->input('status'),
                'montant_HT' => $request->input('montant_HT'),
                'montant_TTC' => $request->input('montant_TTC'),
                'prix_final' => $request->input('montant_TTC'),
                'tva' => $request->input('tva'),
                'fournisseur_id' => $request->input('fournisseur_id'),
                'marque_id' => $request->input('marque_id')
            ]);

            $Produit =  DB::table('produits')
            ->where('produits.id', $id)
            ->where('produits.boutique_id', $ID_Boutique)
            ->first();

            DB::table('stocks')
            ->where('stocks.id', $Produit->stock_id)
            ->update([
                'quantite' => $request->input('quantite'),
            ]);

            $image = $request->file('image');

            if( $image ) {

                $extension = $image->getClientOriginalExtension();
                Storage::disk('public')->put('Produit/Produit-'.$id.'.'.$extension,  File::get($image));

                DB::table('photos_produits')
                ->where('photos_produits.produit_id', $id)
                ->where('photos_produits.order', 0)
                ->update([
                    'src' =>  'Produit-'.$id.'.'.$extension
                ]);

            }

            $categories = json_decode($request->get('categories'));

            DB::table('categorie_produits')
            ->where('categorie_produits.produit_id', '=', $id)
            ->delete();

            if($categories) {

                for ($i=0; $i < count($categories) ; $i++) { 

                    $CategoryProduit = new \App\categorie_produit;
                    $CategoryProduit->produit_id = $id;
                    $CategoryProduit->categorie_id = $categories[$i]->id;    
                    $CategoryProduit->save();

                }

            }

            return response()->json(array('id' => $id), 200);

        }

    }

    public function showProduit($id) {

        $ID_Boutique = $this->guard()->user()->boutique_id;

        if($id) {

            $Produit = DB::table('produits')
            ->join('stocks', 'stocks.id', '=', 'produits.stock_id')
            ->join('photos_produits', 'photos_produits.produit_id', '=', 'produits.id')
            ->where('produits.boutique_id', '=', $ID_Boutique)
            ->where('produits.id', '=', $id)
            ->where('photos_produits.order', '=', '0')
            ->select('produits.*', 'stocks.quantite as quantite', 'photos_produits.src as image')
            ->first();

            $CatgeorieProduit = DB::table('categorie_produits')
            ->where('categorie_produits.produit_id', '=', $id)
            ->get();

            $Produit->categories=$CatgeorieProduit;

            if($Produit) {
                return response()->json($Produit, 200);
            }else {
                return response()->json(false, 404);
            }

        }

    }

    public function DeleteProduit($id) {

        $Produit = new \App\produit;
        $Produit = $Produit::find($id);
        $Result = false;

        if($Produit) {

            $Result = $Produit->delete();

            DB::table('stocks')
            ->where('stocks.id', '=', $Produit->stock_id)
            ->delete();

        }

        return response()->json($Result);

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

        $ID_Boutique = $this->guard()->user()->boutique_id;

        if($id) {

            $Categorie = DB::table('categories')
            ->where('categories.id', '=', $id)
            ->where('categories.boutique_id', '=', $ID_Boutique)
            ->first();

            if($Categorie) {
                return response()->json($Categorie, 200);
            }else {
                return response()->json(false, 404);
            }

        }

    }

    public function StoreCategory(Request $request) {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $NewCategory = new \App\categorie;
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

        $Categorys = new \App\categorie;

        $Category = $Categorys::find($id);

        if($Category) {

            $Category->nom = $request->input('nom');
            $Category->description = $request->input('description');
            $Category->status = $request->input('status');
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

    public function DeleteCategory($id) {

        $Categorys = new \App\categorie;
        $Category = $Categorys::find($id);
        $Result = false;

        if($Category) {

            $Result = $Category->delete();

        }

        return response()->json($Result);

    }

    /* Code Reduction */

    public function AllReduction() {

        $reductions = \App\reduction::all();

        return $reductions;

    }

    public function showReduction($id) {

        $ID_Boutique = $this->guard()->user()->boutique_id;

        if($id) {

            $Reduction = DB::table('reductions')
            ->where('reductions.id', '=', $id)
            ->where('reductions.boutique_id', '=', $ID_Boutique)
            ->first();

            if($Reduction) {
                return response()->json($Reduction, 200);
            }else {
                return response()->json(false, 404);
            }

        }

    }

    public function StoreReduction(Request $request) {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Reduction = new \App\reduction;
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

        $Reductions = new \App\reduction;

        $Reduction = $Reductions::find($id);

        if($Reduction) {

            $Reduction->nom = $request->input('nom');
            $Reduction->description = $request->input('description');
            $Reduction->code = $request->input('code');
            $Reduction->quantite = $request->input('quantite');
            $Reduction->valide = $request->input('valide');
            $Reduction->status = $request->input('status');
            $Reduction->update();

            return response()->json(array('id' => $Reduction->id), 200);

        }

    }

    public function DeleteReduction($id) {

        $reductions = new \App\reduction;

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

        $ID_Boutique = $this->guard()->user()->boutique_id;

        if($id) {

            $Fournisseur = DB::table('fournisseurs')
            ->where('fournisseurs.id', '=', $id)
            ->where('fournisseurs.boutique_id', '=', $ID_Boutique)
            ->first();

            if($Fournisseur) {
                return response()->json($Fournisseur, 200);
            }else {
                return response()->json(false, 404);
            }

        }

    }

    public function StoreFournisseur(Request $request) {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Fournisseur = new \App\fournisseur;

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

        $Fournisseurs = new \App\fournisseur;

        $Fournisseur = $Fournisseurs::find($id);

        if($Fournisseur) {

            $Fournisseur->nom = $request->input('nom');
            $Fournisseur->telephone = $request->input('telephone');
            $Fournisseur->email = $request->input('email');
            $Fournisseur->adresse = $request->input('adresse');
            $Fournisseur->status = $request->input('status');
            $Fournisseur->update();

            return response()->json(array('id' => $Fournisseur->id), 200);

        }

    }

    public function DeleteFournisseur($id) {

        $Fournisseurs = new \App\fournisseur;

        $Fournisseur = $Fournisseurs::find($id);

        $Result = false;

        if($Fournisseur) {

            $Produits = DB::table('produits')
            ->where('produits.fournisseur_id', '=', $id)
            ->update(['fournisseur_id' => null]);

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

        $ID_Boutique = $this->guard()->user()->boutique_id;

        if($id) {

            $Marque = DB::table('marques')
            ->where('marques.id', '=', $id)
            ->where('marques.boutique_id', '=', $ID_Boutique)
            ->first();

            if($Marque) {
                return response()->json($Marque, 200);
            }else {
                return response()->json(false, 404);
            }

        }

    }

    public function StoreMarque(Request $request) {

        $id = $this->guard()->user()->boutique_id;

        if($id) {

            $Marque = new \App\marque;

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

        $Marques = new \App\marque;

        $Marque = $Marques::find($id);

        if($Marque) {

            $Marque->nom = $request->input('nom');
            $Marque->description = $request->input('description');
            $Marque->status = $request->input('status');
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

    public function DeleteMarque($id) {

        $Marques = new \App\marque;

        $Marque = $Marques::find($id);

        $Result = false;

        if($Marque) {

            $Produits = DB::table('produits')
            ->where('produits.marque_id', '=', $id)
            ->update(['marque_id' => null]);

            $Result = $Marque->delete();

        }

        return response()->json($Result);

    }

    public function guard() {

        return Auth::guard();

    }

}
