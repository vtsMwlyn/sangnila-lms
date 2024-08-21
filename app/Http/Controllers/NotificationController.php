<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function mark_as_read($notification_id){
		$notif = Notification::findOrFail($notification_id);
		$notif->update(["status" => "read"]);

		return back()->with("successNotifAction", "Action on notification is success");
	}

	public function mark_all_as_read(){
		foreach(Auth::user()->inboxes as $notif){
			$notif->update(["status" => "read"]);
		}

		return back()->with("successNotifAction", "Action on notification is success");
	}

	public function dismiss($notification_id){
		Notification::destroy($notification_id);

		return back()->with("successNotifAction", "Action on notification is success");
	}

	public function dismiss_all(){
		foreach(Auth::user()->inboxes as $notif){
			if($notif->status == "read"){
				Notification::destroy($notif->id);
			}
		}

		return back()->with("successNotifAction", "Action on notification is success");
	}
}
