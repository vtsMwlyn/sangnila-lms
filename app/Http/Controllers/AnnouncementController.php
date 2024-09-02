<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Announcement;
use App\Models\AnnouncementUser;
use Illuminate\Http\Request;
use App\Rules\MinimumOneCheckbox;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
			"announce_from" => "required|date",
			"announce_until" => "required|date",
			"receiver" => ["required", new MinimumOneCheckbox]
		]);

		if($request->file("image")){
			$validatedData["image"] = $request->file("image")->store("announcement-images");
		}

		$receiver_array = [];

		foreach ($validatedData["receiver"] as $receiver_role) {
			array_push($receiver_array, $receiver_role);
		}

		$data_to_create = [
			"title" => $validatedData["title"],
			"content" => $validatedData["content"],
			"announce_from" => $validatedData["announce_from"],
			"announce_until" => $validatedData["announce_until"] . " 23:59:59",
			"sent_to" => json_encode($receiver_array)
		];

		if($request->file("image")){
			$data_to_create["image_path"] = $validatedData["image"];
		}

		try {
			Announcement::create($data_to_create);
		}
		catch(Exception $e){
			return back()->with("systemFail", "System failed to create announcement, please report the error to our IT team. Error detail: " . $e->getMessage());
		}


		return redirect(route("admin.announcement.index"))->with("successUploadAnnouncement", "Announcement uploaded successfully!");
	}

	public function edit($announcement_id){
		return view("roles.admin.announcement.edit", [
			"announcement" => Announcement::findOrFail($announcement_id)
		]);
	}

	public function update(Request $request, $announcement_id){
		$validatedData = $request->validate([
			"title" => "required|min:3",
			"content" => "required|min:3",
			"image" => "image|file|max:4096",
			"announce_from" => "required|date",
			"announce_until" => "required|date",
			"receiver" => ["required", new MinimumOneCheckbox]
		]);

		$announcement = Announcement::findOrFail($announcement_id);

        $old_image_path = "";

		if($request->file("image")){
			if($announcement->image_path){
				$old_image_path = $announcement->image_path;
			}
			$validatedData["image"] = $request->file("image")->store("announcement-images");
		}

		$receiver_array = [];

		foreach ($validatedData["receiver"] as $receiver_role) {
			array_push($receiver_array, $receiver_role);
		}

		$data_to_update = [
			"title" => $validatedData["title"],
			"content" => $validatedData["content"],
			"sent_to" => json_encode($receiver_array),
			"announce_from" => $validatedData["announce_from"],
			"announce_until" => $validatedData["announce_until"] . " 23:59:59",
		];

		if($request->file("image")){
			$data_to_update["image_path"] = $validatedData["image"];
		}

		try {
			$announcement->update($data_to_update);

			if($old_image_path != ""){
				Storage::delete($old_image_path);
			}
		}
		catch(Exception $e){
			if($request->file("image")){
				Storage::delete($validatedData["image"]);
			}

			return back()->with("systemFail", "System failed to edit announcement, please report the error to our IT team. Error detail: " . $e->getMessage());
		}

		return redirect(route("admin.announcement.index"))->with("successEditAnnouncement", "Announcement edited successfully!");
	}

	public function delete($announcement_id){
		return view("roles.admin.announcement.delete", [
			"announcement" => Announcement::findOrFail($announcement_id)
		]);
	}

	public function destroy($announcement_id){
		$announcement = Announcement::findOrFail($announcement_id);

		if($announcement->image_path){
			Storage::delete($announcement->image_path);
		}

		Announcement::destroy($announcement_id);

		return redirect(route("admin.announcement.index"))->with("successDeleteAnnouncement", "Announcement deleted successfully!");
	}
}
