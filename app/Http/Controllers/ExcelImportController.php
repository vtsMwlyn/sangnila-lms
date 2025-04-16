<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Topic;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\CurriculumTopic;
use App\Imports\CurriculumsImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UserAndDetailsImport;
use App\Imports\TopicsAndActivitiesImport;

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

			return redirect(route("admin.student.index"))->with('success', 'Students data imported successfully!');
		}

		catch (Exception $e){
			return back()->with('danger', "Data in your file is in invalid format or not fully filled. Please recheck your data, revise, make sure it fullfil the requirement, and then try to upload again.");
		}
	}

	public function import_excel_curriculum_index($course_id){
		return view("roles.admin.curriculum.import-excel-curriculum", [
			"course" => Course::findOrFail($course_id)
		]);
	}

	public function import_excel_curriculum_store(Request $request, $course_id){
		$request->validate([
			'file' => 'required|mimes:xlsx,xls,csv',
		]);

		try {
			$course = Course::findOrFail($course_id);

			DB::beginTransaction();

			// Remove the old data
			$old_ctopics = CurriculumTopic::where("course_id", $course->id)->delete();

			// Add with the new data
			Excel::import(new CurriculumsImport($course_id), $request->file('file')->store('temp'));

			DB::commit();

			return redirect(route("admin.course.show", $course_id))->with('success', 'Curriculum data imported successfully!');
		}

		catch (Exception $e){
			// throw $e;

			DB::rollback();

			return back()->with('danger', "Data in your file is in invalid format or not fully filled. Please recheck your data, revise, make sure it fullfil the requirement, and then try to upload again.");
		}
	}

	public function import_excel_topics_and_activities_index($course_id){
		return view("roles.teacher.mycourse.import-excel-topics-and-activities", [
			"course" => Course::findOrFail($course_id)
		]);
	}

	public function import_excel_topics_and_activities_store(Request $request, $course_id){
		$request->validate([
			'file' => 'required|mimes:xlsx,xls,csv',
		]);

		try {
			$course = Course::findOrFail($course_id);

			DB::beginTransaction();

			// Remove the old data
			$old_topics = Topic::where("course_id", $course->id)->delete();

			// Add with the new data
			Excel::import(new TopicsAndActivitiesImport($course_id), $request->file('file')->store('temp'));

			DB::commit();

			return redirect(route("teacher.mycourse.show", ['course_id' => $course_id, 'content' => 'topics and activities']))->with('success', 'Topics and Activities data imported successfully!');
		}

		catch (Exception $e){
			DB::rollback();

			return back()->with('danger', "Data in your file is in invalid format or not fully filled. Please recheck your data, revise, make sure it fullfil the requirement, and then try to upload again.");
		}
	}
}
