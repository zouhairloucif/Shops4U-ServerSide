<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CatalogueController extends Controller {

    public function __construct() {

        //$this->middleware('jwt');

    }

    public function AllCategories() {

        $categories = \App\Categorie::all();

        return $categories;

    }

    public function StoreCategories(Request $request) {

        $NewCategories = new \App\Categorie;
        $NewCategories->nom = $request->input('nom');
        $NewCategories->description = $request->input('description');
        $NewCategories->image = $request->input('image');
        $NewCategories->utilisateur_id = $request->input('utilisateur_id');
        $NewCategories->parent = $request->input('parent');
        $ResultCategories = $NewCategories->save();

        return response()->json(array('success' => true, 'id' => $NewCategories->id), 200);

    }

}
