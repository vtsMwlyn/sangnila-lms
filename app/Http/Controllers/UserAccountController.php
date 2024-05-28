<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Rules\MatchPasswords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserAccountController extends Controller{
    public function show(){
		switch(Auth::user()->role->id){
			case 1:
				return view("roles.admin.profile", [
					"account_data" => User::where("id", Auth::user()->id)->first()
				]);
				break;

			case 2:
				return view("roles.teacher.profile", [
					"account_data" => User::where("id", Auth::user()->id)->first()
				]);
				break;

			case 3:
				return view("roles.student.profile", [
					"account_data" => User::where("id", Auth::user()->id)->first()
				]);
				break;

		}
	}

	public function update(Request $request){
		$validationRule = [
			"full_name" => "required|min:3",
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

		User::where("id", Auth::user()->id)->update($validatedData);

		return back()->with("successUpdateProfile", "Account profile updated successfully!");
	}
}
