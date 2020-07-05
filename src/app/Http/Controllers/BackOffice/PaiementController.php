<?php

namespace App\Http\Controllers\BackOffice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaiementController extends Controller {

    public function __construct() {

        $this->middleware('jwt');

    }

    public function modePaiements()  {
        
        $Mode_paiements = \App\mode_paiement::all();

        return $Mode_paiements;

    }
    
}
