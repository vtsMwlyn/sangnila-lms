<?php

namespace App\Http\Controllers;

use App\Exports\LecturerInvoiceExport;
use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\LecturerInvoice;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class LecturerInvoiceController extends Controller
{
    public function create($course_id){
        $course = Course::findOrFail($course_id);

        return view('roles.teacher.lecturer-invoice.create', [
            'course' => $course,
        ]);
    }

    public function store(Request $request, $course_id){
        $validatedData = $request->validate([
            'number' => 'required',
            'date' => 'required',
            'bank_data' => 'required',
            'rate' => 'required'
        ]);

        $course = Course::findOrFail($course_id);

        $validatedData['user_id'] = Auth::user()->id;
        $validatedData['course_id'] = $course->id;

        LecturerInvoice::create($validatedData);

        return redirect(route('teacher.attendance.show', $course->id))->withQuery(['content' => request('content')])->with('success', 'Successfully stored the invoice data!');
    }

    public function download($invoice_id){
        $invoice = LecturerInvoice::findOrFail($invoice_id);
        $course = $invoice->course;

        $today = Carbon::today();
        $last_25th = $today->copy()->subMonth()->day(25);
        $last_26th = $last_25th->copy()->subMonth()->day(26);

        $student_attendances = StudentAttendance::whereHas('attendance', function($query) use($course, $last_26th, $last_25th){
            return $query->where('course_id', $course->id)->where('attendance_date', '>=', $last_26th)->where('attendance_date', '<=', $last_25th);
        })->with('attendance')->orderBy('student_id')->get()->groupBy('student_id');

        return Excel::download(new LecturerInvoiceExport($invoice, $student_attendances), 'lecturer_invoice_' . Auth::user()->full_name . '_' . $course->full_name . '.xlsx');
    }
}
