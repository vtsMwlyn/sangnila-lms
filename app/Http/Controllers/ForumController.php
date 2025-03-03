<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Course;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ForumController extends Controller
{
    public function index_teacher(Request $request){
        $course_id = $request->course ?? -1;

        return view('roles.teacher.forum-discussion.index', [
            'course_id' => $course_id
        ]);
    }

    public function index_student(Request $request){
        $course_id = $request->course ?? -1;

        return view('roles.student.forum-discussion.index', [
            'course_id' => $course_id
        ]);
    }

    public function retrieve_message($course_id){
        $course = Course::findOrFail($course_id);

        return response()->json(Message::where('course_id', $course->id)->with('user.details')->orderBy('created_at')->get());
    }

    public function send_message(Request $request, $course_id){
        $request->validate([
            'message' => 'nullable|string',
            'attachment' => 'file|nullable'
        ]);

        try {
            DB::beginTransaction();

            $course = Course::findOrFail($course_id);

            $path = null;
            if($request->file('attachment')){
                $path = $request->file('attachment')->store('message-attachment');
            }

            $msg = Message::create([
                'course_id' => $course->id,
                'user_id' => Auth::user()->id,
                'message' => $request->message ?? null,
                'attachment_path' => $path,
            ]);

            DB::commit();
        }
        catch(Exception $e){
            DB::rollback();

            Storage::delete($path);

            dd($e->getMessage());
        }

        return response()->json($msg);
    }
}
