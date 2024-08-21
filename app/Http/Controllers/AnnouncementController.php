<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Announcement;
use App\Models\AnnouncementUser;
use Illuminate\Http\Request;
use App\Rules\MinimumOneCheckbox;
use Exception;
use Illuminate\Support\Facades\DB;

class AnnouncementController extends Controller
{
	public function index(){
		return view("roles.admin.announcement.index", [
			"announcements" => Announcement::all()
		]);
	}

    public function create(){
		return view("roles.admin.announcement.create");
	}

	public function store(Request $request){
		$validatedData = $request->validate([
			"title" => "required|min:3",
			"content" => "required|min:3",
			"image" => "image|file|max:4096",
			"receiver" => ["required", new MinimumOneCheckbox]
		]);

		if($request->file("image")){
			$validatedData["image"] = $request->file("image")->store("announcement-images");
		}

		try{
			DB::beginTransaction();

			$receiver_array = [];

			foreach ($validatedData["receiver"] as $receiver_role) {
				array_push($receiver_array, $receiver_role);
			}

			$data_to_create = [
				"title" => $validatedData["title"],
				"content" => $validatedData["content"],
				"sent_to" => json_encode($receiver_array)
			];

			if($request->file("image")){
				$data_to_create["image_path"] = $validatedData["image"];
			}

			$newAnnouncement = Announcement::create($data_to_create);

			foreach($validatedData["receiver"] as $index => $receiver_role){
				if($receiver_role == "on"){
					foreach(Role::findOrFail($index + 1)->users as $user){
						AnnouncementUser::create([
							"announcement_id" => $newAnnouncement->id,
							"user_id" => $user->id
						]);
					}
				}
			}

			DB::commit();
		}
		catch (Exception $e){
			DB::rollback();

			return $e->getMessage();

			return back()->with("failUploadAnnouncement", "System failed to upload announcement");
		}

		return  redirect(route("admin.announcement.index"))->with("successUploadAnnouncement", "Announcement uploaded successfully!");
	}

	public function edit($announcement_id){
		return view("roles.admin.announcement.edit", [
			"announcement" => Announcement::findOrFail($announcement_id)
		]);
	}

	public function update(Request $request, $announcement_id){
		return $request;
	}

	public function delete($announcement_id){
		return view("roles.admin.announcement.delete", [
			"announcement" => Announcement::findOrFail($announcement_id)
		]);
	}

	public function destroy($announcement_id){
		return "Otw di-delete";
	}
}
