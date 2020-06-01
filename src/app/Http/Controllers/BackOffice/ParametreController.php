<?php

namespace App\Http\Controllers\BackOffice;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class ParametreController extends Controller {

    public function __construct() {

        $this->middleware('jwt', ['except' => ['signup']]);

    }

    
}
