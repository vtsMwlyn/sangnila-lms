<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use App\Rules\MatchPasswords;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Exp;

class UserAccountController extends Controller{
    public function show(){
		switch(Auth::user()->role->id){
			case 1:
				return view("roles.admin.profile", [
					"account_data" => User::findOrFail(Auth::user()->id)
				]);
				break;

			case 2:
				return view("roles.teacher.profile", [
					"account_data" => User::findOrFail(Auth::user()->id)
				]);
				break;

			case 3:
				return view("roles.student.profile", [
					"account_data" => User::findOrFail(Auth::user()->id)
				]);
				break;

		}
	}

	public function update(Request $request){
		$validationRule = [
			"full_name" => "required|min:3",
			"phone_number" => ["nullable", 'min:4', 'regex:/^(0|\+)([0-9]+[\s|-]?)+$/'],
		];

		if($request->password){
			$validationRule["password"] = ["required", "min:8", new MatchPasswords];
			$validationRule["password_confirmation"] = "required|min:8";
		}

		$validatedData = $request->validate($validationRule);

		if($request->password){
			$validatedData["password"] = Hash::make($validatedData["password"]);
			unset($validatedData["password_confirmation"]);
		}

		try {
			DB::beginTransaction();

			$user_detail = Auth::user()->details;

			$old_image_path = "";
			if($request->file("cropped_image")){
				if($user_detail->profpic){
					$old_image_path = $user_detail->profpic;
				}
				$new_profpic_path = $request->file("cropped_image")->store("profpic-images");

				UserDetail::where("user_id", Auth::user()->id)->update([
					"profpic" => $new_profpic_path
				]);
			}

			if($request->password){
				User::findOrFail(Auth::user()->id)->update([
					"password" => $validatedData["password"]
				]);
			}

			User::findOrFail(Auth::user()->id)->update([
				"full_name" => $validatedData["full_name"],
				"phone_number" => $validatedData["phone_number"],
			]);

			if($old_image_path != ""){
				Storage::disk('public')->delete($old_image_path);
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			if($request->file("cropped_image")){
				Storage::disk('public')->delete($validatedData["cropped_image"]);
			}

			throw $e;

			return back()->with("danger", "System failed to edit profile, please report the error to our IT team. Error detail: " . $e);
		}

		return back()->with("success", "Account profile updated successfully!");
	}
}
