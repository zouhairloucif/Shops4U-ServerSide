<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller {

	public function __construct() {

		$this->middleware('jwt', ['except' => ['signup']]);

	}

	public function signup(Request $request) {

		$user = User::create([
			'nom' => $request->nom,
			'prenom' => $request->prenom,
			'email' => $request->email,
			'password' => bcrypt($request->password),
			'role_id' => $request->role_id,
		]);

		$token = $this->guard()->login($user);

		return $this->respondWithToken($token);

	}


	protected function respondWithToken($token) {

		$role_id = auth()->user()->role_id;
		$role = \DB::table('roles')->where('id', $role_id)->pluck('nom');

		return response()->json([
			'access_token' => $token,
			'token_type' => 'bearer',
			'expires_in' => $this->guard()->factory()->getTTL() * 60,
			'role' => $role,
		]);

	}

	public function guard() {

		return Auth::guard();

	}

}
