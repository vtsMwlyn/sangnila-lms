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
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;

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
                return Carbon::parse($item->attendance->attendance_date)->format('m-y'); // Group by Year-Month first
            })
            ->sortKeys()
            ->map(function ($groupedByMonth) {
                return $groupedByMonth->groupBy(function ($item) {
                    return $item->attendance->course->id; // Group by course name
                })->map(function ($groupedByCourse) {
                    return $groupedByCourse->groupBy(function ($item) {
                        return Carbon::parse($item->attendance->attendance_date)->format('Y/m/d') . '-' . Carbon::parse($item->start_time)->format('H:i:s') . '-' . Carbon::parse($item->end_time)->format('H:i:s'); // Group by time range
                    });
                });
            });


        if(count($student_attendances) == 0){
            return back()->with('danger', 'There are no student attendance data between ' . $last_26th->format('l, d M Y') . ' and ' . $last_25th->format('l, d M Y') . ', cannot generate invoice!');
        }
    
        // return $student_attendances;

        $reimburses = Reimburse::where('user_id', Auth::user()->id)->whereBetween('date', [$last_26th, $last_25th])->orderBy('date', 'asc')->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->date)->format('m-y'); // Group by Year-Month first
            })
            ->sortKeys();

        // return $reimburses;

        return Excel::download(new LecturerInvoiceExport($invoice, $student_attendances, $reimburses), 'lecturer_invoice_' . Auth::user()->full_name . '_' . '.xlsx');
    }

    public function teacher_create_reimburse(){
        return view('roles.teacher.lecturer-invoice-reimburse.create-reimburse');
    }

    public function teacher_store_reimburse(Request $request){
        $validatedData = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'image' => 'required|file|image',
            'need' => 'required',
        ]);

        $imageFile = $request->file('image');
        $randomName = Str::random(40) . '.webp';
        $relativePath = 'reimburse/' . $randomName;
        $fullPath = storage_path('app/public/' . $relativePath);
    
        $manager = new ImageManager(new Driver());
        $image = $manager->read($imageFile->getRealPath());
        $image->toWebp(80)->save($fullPath);

        $validatedData['evidence_path'] = $relativePath;
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
            Storage::disk('public')->delete($evpath);

            $imageFile = $request->file('image');
            $randomName = Str::random(40) . '.webp';
            $relativePath = 'reimburse/' . $randomName;
            $fullPath = storage_path('app/public/' . $relativePath);
        
            $manager = new ImageManager(new Driver());
            $image = $manager->read($imageFile->getRealPath());
            $image->toWebp(80)->save($fullPath);

            $evpath = $relativePath;
        }

        $validatedData['evidence_path'] = $evpath;

        $reimburse->update($validatedData);

        return redirect(route('teacher.lecturer-invoice-reimburse.index', ['content' => request('content')]))->with('success', 'The reimburse data has been edited successfully!');
    }
}
