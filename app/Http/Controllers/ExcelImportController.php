<?php

namespace App\Http\Controllers;

use App\Imports\UserAndDetailsImport;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelImportController extends Controller
{
	public function import_excel_student_index(){
		return view("roles.admin.student.import-excel-student");
	}

    public function import_excel_student_store(Request $request)
	{
		$request->validate([
			'file' => 'required|mimes:xlsx,xls,csv',
		]);

		try {
			Excel::import(new UserAndDetailsImport, $request->file('file')->store('temp'));

			return redirect(route("admin.student.index"))->with('successImportExcelStudent', 'Students data imported successfully!');
		}

		catch (Exception $e){
			return back()->with('failImportExcelStudent', "Data in your file is in invalid format or not fully filled. Please recheck your data, revise, make sure it fullfil the requirement, and then try to upload again.");
		}
	}
}
