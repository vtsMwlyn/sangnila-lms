<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Course;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use App\Models\CourseTeacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ForumController extends Controller
{
    public function index_teacher(Request $request){
        $course_id = $request->course ?? -1;

        return view('roles.teacher.forum-discussion.index', [
            'course_id' => $course_id,
        ]);
    }

    public function index_student(Request $request){
        $course_id = $request->course ?? -1;

        return view('roles.student.forum-discussion.index', [
            'course_id' => $course_id,
        ]);
    }

    public function retrieve_message_teacher($course_id) {
        $course = Course::findOrFail($course_id);
        
        try {
            $studentIds = CourseStudent::where('course_id', $course->id)
            ->where('teacher_id', Auth::id())
            ->pluck('student_id');
    
            $messages = Message::where(function ($query) use ($studentIds) {
                    $query->where('user_id', Auth::id())
                        ->orWhereIn('user_id', $studentIds);
                })->where('course_id', $course->id)
                ->with('user.details')
                ->orderBy('created_at')
                ->get();
        }
        catch(Exception $e){
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    
        return response()->json($messages);
    }
    

    public function retrieve_message_student($course_id) {
        $course = Course::findOrFail($course_id);

        try {
            $currentClass = CourseStudent::where('course_id', $course->id)
                ->where('student_id', Auth::id())
                ->with('teacher') // Eager load teacher to avoid an extra query
                ->firstOrFail();
        
            $teacher = $currentClass->teacher;
        
            // Get all student IDs directly
            $studentIds = CourseStudent::where('course_id', $course->id)
                ->where('teacher_id', $teacher->id)
                ->pluck('student_id');
        
            $messages = Message::where(function ($query) use ($studentIds, $teacher) {
                    $query->where('user_id', Auth::id())
                        ->orWhereIn('user_id', $studentIds)
                        ->orWhere('user_id', $teacher->id);
                })->where('course_id', $course->id)
                ->with('user.details')
                ->orderBy('created_at')
                ->get();
        }
        catch(Exception $e){
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    
        return response()->json($messages);
    }
    

    public function send_message_teacher(Request $request, $course_id){
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
                'user_id' => Auth::user()->id,
                'course_id' => $course_id,
                'message' => $request->message ?? null,
                'attachment_path' => $path,
            ]);

            DB::commit();
        }
        catch(Exception $e){
            DB::rollback();

            Storage::delete($path);

            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }

        return response()->json($msg);
    }

    public function send_message_student(Request $request, $course_id){
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
                'user_id' => Auth::user()->id,
                'course_id' => $course_id,
                'message' => $request->message ?? null,
                'attachment_path' => $path,
            ]);

            DB::commit();
        }
        catch(Exception $e){
            DB::rollback();

            Storage::delete($path);

            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }

        return response()->json($msg);
    }
}
