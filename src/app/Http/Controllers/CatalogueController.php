<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CatalogueController extends Controller {

    public function __construct() {

        //$this->middleware('jwt');

    }

    public function AllCategory() {

        $categories = \App\Categorie::all();

        return $categories;

    }

    public function showCategory($id) {

        $Categorys = new \App\Categorie;

        $Category = $Categorys::find($id);

        if( $Category ) {
            return $Category;
        }else {
            return false;
        }

    }

    public function StoreCategory(Request $request) {

        $NewCategory = new \App\Categorie;
        $NewCategory->nom = $request->input('nom');
        $NewCategory->description = $request->input('description');
        $NewCategory->utilisateur_id = $request->input('utilisateur_id');
        $NewCategory->parent = $request->input('parent');
        $ReCategory = $NewCategory->save();

        $image = $request->file('image');

        if( $image ) {
            $extension = $image->getClientOriginalExtension();
            Storage::disk('public')->put('Category/Category-'.$NewCategory->id.'.'.$extension,  File::get($image));
            $NewCategory->image = 'Category-'.$NewCategory->id;
            $NewCategory->update();
        }

        return response()->json(array('id' => $NewCategory->id), 200);

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
                $Category->image = 'Category-'.$Category->id;
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

}
