<?php

namespace App\Http\Controllers\BackOffice;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;

class BoutiqueController extends Controller {

	public function __construct() {

		$this->middleware('jwt', ['except' => ['signup']]);

	}

	public function showboutique() {

		$id = $this->guard()->user()->boutique_id;

		$Boutiques = new \App\boutique;

		$Boutique = $Boutiques::find($id);

		if($Boutique) {
			return $Boutique;
		}else {
			return response()->json(false);
		}

	}

	public function StoreBoutique(Request $request) {

		$Boutique = new \App\boutique;
		$Boutique->nom = $request->input('nom');
		$Boutique->url = $request->input('url');
		$Boutique->devise = $request->input('devise');
		$Boutique->langue = $request->input('langue');
		$Boutique->status = "0";
		$Boutique->save();

		$logo = $request->file('logo');

		if( $logo ) {

			$extension = $logo->getClientOriginalExtension();
			Storage::disk('public')->put('Boutique/Boutique-'.$Boutique->id.'.'.$extension,  File::get($logo));
			$Boutique->logo = 'Category-'.$Boutique->id.'.'.$extension;
			$Boutique->update();
		}

		if($Boutique->id) {

			$user = auth()->user();
			$user->boutique_id=$Boutique->id;
			$user->update();

		}

		return response()->json(array('id' => $Boutique->id), 200);

	}

	public function UpdateBoutique(Request $request) {

		$id = $this->guard()->user()->boutique_id;

		$Boutiques = new \App\boutique;

		$Boutique = $Boutiques::find($id);

		if($Boutique) {

			$Boutique->nom = $request->input('nom');
			$Boutique->url = $request->input('url');
			$Boutique->devise = $request->input('devise');
			$Boutique->langue = $request->input('langue');
			$Boutique->status = "0";
			$Boutique->update();

			$logo = $request->file('logo');

			if( $logo ) {

				$extension = $logo->getClientOriginalExtension();
				Storage::disk('public')->put('Boutique/Boutique-'.$Boutique->id.'.'.$extension,  File::get($logo));
				$Boutique->logo = 'Category-'.$Boutique->id.'.'.$extension;
				$Boutique->update();

			}

			return response()->json(array('id' => $Boutique->id), 200);

		}

	}

	public function showMaintenance(Request $request) {

		$id = $this->guard()->user()->boutique_id;

		if($id) {

			$Boutiques = new \App\boutique;

			$Boutique = $Boutiques::find($id);

			$maintenance_id = $Boutique->maintenance_id;

			if($maintenance_id) {

				$Maintenances = new \App\maintenance;
				$Maintenance = $Maintenances::find($maintenance_id);

				return $Maintenance;

			}

		}

		return response()->json(false);

	}

	public function maintenance(Request $request) {

		$id = $this->guard()->user()->boutique_id;

		if($id) {

			$Boutiques = new \App\boutique;

			$Boutique = $Boutiques::find($id);

			$maintenance_id = $Boutique->maintenance_id;

			if( $maintenance_id ) {

				$Maintenances = new \App\maintenance;
				$Maintenance = $Maintenances::find($maintenance_id);
				$Maintenance->message = $request->input('message');
				$Maintenance->status = $request->input('status');
				$Maintenance->update();

				return response()->json(array('id' => $Maintenance->id), 200);

			}else {

				$Maintenance = new \App\maintenance;
				$Maintenance->message = $request->input('message');
				$Maintenance->status = $request->input('status');
				$Maintenance->save();
				$Boutique->maintenance_id = $Maintenance->id;
				$Boutique->update();

				return response()->json(array('id' => $Maintenance->id), 200);

			}

		}

	}

	public function guard() {

		return Auth::guard();

	}


}
