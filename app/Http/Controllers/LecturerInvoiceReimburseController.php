<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Course;
use App\Models\Reimburse;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use App\Models\LecturerInvoice;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LecturerInvoiceExport;
use Illuminate\Support\Facades\Storage;

class LecturerInvoiceReimburseController extends Controller
{
    public function teacher_index(){
        return view('roles.teacher.lecturer-invoice-reimburse.index');
    }

    public function teacher_create_invoice(){
        return view('roles.teacher.lecturer-invoice-reimburse.create-invoice');
    }

    public function teacher_store_invoice(Request $request){
        $validatedData = $request->validate([
            'number' => 'required',
            'date' => 'required',
            'bank_data' => 'required',
        ]);

        $validatedData['user_id'] = Auth::user()->id;

        LecturerInvoice::create($validatedData);

        return redirect(route('teacher.lecturer-invoice-reimburse.index'))->with('success', 'Successfully stored the invoice data!');
    }

    public function teacher_edit_invoice($invoice_id){
        return view('roles.teacher.lecturer-invoice-reimburse.edit-invoice', [
            'invoice' => LecturerInvoice::findOrFail($invoice_id)
        ]);
    }

    public function teacher_update_invoice(Request $request, $invoice_id){
        $validatedData = $request->validate([
            'number' => 'required',
            'date' => 'required',
            'bank_data' => 'required',
        ]);

        $validatedData['user_id'] = Auth::user()->id;

        LecturerInvoice::findOrFail($invoice_id)->update($validatedData);

        return redirect(route('teacher.lecturer-invoice-reimburse.index'))->with('success', 'Successfully edited the invoice data!');
    }

    public function teacher_download_invoice($invoice_id){
        $invoice = LecturerInvoice::findOrFail($invoice_id);

        $invoice_date = Carbon::parse($invoice->date);
        $last_25th = $invoice_date->day >= 25 ? $invoice_date->day(25) : $invoice_date->subMonth()->day(25);
        $last_26th = $last_25th->copy()->subMonth()->day(26);

        $teached_student_list = CourseStudent::where('teacher_id', Auth::user()->id)->pluck('student_id')->toArray();
        $teached_course_list = CourseStudent::where('teacher_id', Auth::user()->id)->distinct()->pluck('course_id')->toArray();

        $student_attendances = StudentAttendance::whereIn('student_id', $teached_student_list)
            ->whereHas('attendance', function ($query) use ($last_26th, $last_25th, $teached_course_list) {
                return $query->whereBetween('attendance_date', [$last_26th, $last_25th])->orderBy('attendance_date', 'asc')->whereIn('course_id', $teached_course_list);
            })
            ->with('attendance')
            ->orderBy('student_id')
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->attendance->attendance_date)->format('M-y'); // Group by Year-Month first
            })
            ->sortKeys()
            ->map(function ($groupedByMonth) {
                return $groupedByMonth->groupBy(function ($item) {
                    return $item->student_id; // Then group by Student ID
                });
            });

        if(count($student_attendances) == 0){
            return back()->with('danger', 'There are no student attendance data between ' . $last_26th->format('l, d M Y') . ' and ' . $last_25th->format('l, d M Y') . ', cannot generate invoice!');
        }
    
        // return $student_attendances;

        return Excel::download(new LecturerInvoiceExport($invoice, $student_attendances), 'lecturer_invoice_' . Auth::user()->full_name . '_' . '.xlsx');
    }

    public function teacher_create_reimburse(){
        return view('roles.teacher.lecturer-invoice-reimburse.create-reimburse');
    }

    public function teacher_store_reimburse(Request $request){
        // return $request;
        $validatedData = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'image' => 'required|file|image',
            'need' => 'required',
        ]);

        $evidence_path = $request->file('image')->store('reimburse');
        $validatedData['evidence_path'] = $evidence_path;

        $validatedData['user_id'] = Auth::user()->id;

        Reimburse::create($validatedData);

        return redirect(route('teacher.lecturer-invoice-reimburse.index', ['content' => request('content')]))->with('success', 'Reimburse data has been added successfully!');
    }

    public function teacher_edit_reimburse($reimburse_id){
        return view('roles.teacher.lecturer-invoice-reimburse.edit-reimburse', [
            'reimburse' => Reimburse::findOrFail($reimburse_id)
        ]);
    }

    public function teacher_update_reimburse(Request $request, $reimburse_id){
        $validatedData = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'image' => 'nullable|file|image',
            'need' => 'required',
        ]);

        $reimburse = Reimburse::findOrFail($reimburse_id);

        $evpath = $reimburse->evidence_path;
        if($request->file('image')){
            Storage::delete($evpath);

            $evpath = $request->file('image')->store('reimburse');
        }

        $validatedData['evidence_path'] = $evpath;

        $reimburse->update($validatedData);

        return redirect(route('teacher.lecturer-invoice-reimburse.index', ['content' => request('content')]))->with('success', 'The reimburse data has been edited successfully!');
    }

    public function teacher_destroy_reimburse($reimburse_id){

    }
}
