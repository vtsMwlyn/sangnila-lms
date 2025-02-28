<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function index(Request $request){
        $course_id = $request->course ?? -1;

        return view('roles.teacher.forum-discussion.index', [
            'course_id' => $course_id
        ]);
    }

    public function retrieve_message($course_id){
        $course = Course::findOrFail($course_id);

        return response()->json(Message::where('course_id', $course->id)->with('user.details')->orderBy('created_at')->get());
    }

    public function send_message(Request $request, $course_id){
        $request->validate([
            'message' => 'required|string'
        ]);

        $course = Course::findOrFail($course_id);

        $msg = Message::create([
            'course_id' => $course->id,
            'user_id' => Auth::user()->id,
            'message' => $request->message,
        ]);

        return response()->json($msg);
    }
}
