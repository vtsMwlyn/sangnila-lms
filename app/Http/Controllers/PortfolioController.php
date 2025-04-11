<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Course;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    // ===== HEAD OF LECTURERS ===== //
    public function head_of_lecturer_index(){
        return view('roles.head-of-lecturer.portfolio.index', [
            'courses' => Course::filter(request(['search']))->orderBy('course_name', 'asc')->orderByRaw('CASE WHEN status = "active" THEN 0 ELSE 1 END')->get()
        ]);
    }

    public function head_of_lecturer_show($course_id){
        $course = Course::findOrFail($course_id);

        return view('roles.head-of-lecturer.portfolio.show', [
            'portfolios' => Portfolio::filter(request(['student', 'teacher']))->where('course_id', $course->id)->orderBy('created_at', 'desc')->paginate(30),
            'course' => $course
        ]);
    }

    public function head_of_lecturer_destroy($portfolio_id){
		$portfolio = Portfolio::findOrFail($portfolio_id);

		if($portfolio->type != 'link'){
			Storage::disk('public')->delete($portfolio->path);
		}

		$portfolio->delete();

		return back()->with('success', 'Successfully deleted the portfolio image for this student!');
	}

    // ===== TEACHER ===== //
    public function teacher_store_portfolio(Request $request, $student_id, $course_id){
		$request->validate([
			'file' => [
				'nullable',
				function ($attribute, $value, $fail) {
					if (!$value->isValid()) {
						$fail('Invalid file uploaded.');
					}
		
					$mimeType = $value->getMimeType();
					if (!str_starts_with($mimeType, 'image/') && !str_starts_with($mimeType, 'video/') && $mimeType !== 'application/pdf') {
						$fail('The file must be an image, video, or pdf.');
					}
				},
			],
			'link' => 'nullable',
		]);

		$paths = [];
		try {
			DB::beginTransaction();

			$course = Course::findOrFail($course_id);
			$student = User::findOrFail($student_id);
			
			if ($request->file('files')) {
				foreach ($request->file('files') as $req_file) {
					$mimeType = $req_file->getMimeType();
					$type = explode('/', $mimeType)[0];
			
					$path = $req_file->store('progress-portfolio');
					$paths[] = $path;
			
					Portfolio::create([
						'student_id' => $student->id,
						'course_id' => $course->id,
						'path' => $path,
						'type' => $type,
					]);
				}
			}
			else if($request->link){
				Portfolio::create([
					'student_id' => $student->id,
					'course_id' => $course->id,
					'path' => $request->link,
					'type' => 'link'
				]);
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			foreach($paths as $p){
				Storage::disk('public')->delete($p);
			}

			return back()->with('danger', 'System failed to upload portfolio images for this student. Please report to our IT team, error detail: ' . $e->getMessage());
		}

		return back()->withQuery(['content' => request('content')])->with('success', 'Successfully uploaded portfolio files for this student!');
	}

	public function teacher_destroy_portfolio($portfolio_id){
		$portfolio = Portfolio::findOrFail($portfolio_id);

		if($portfolio->type != 'link'){
			Storage::disk('public')->delete($portfolio->path);
		}

		$portfolio->delete();

		return back()->withQuery(['content' => request('content')])->with('success', 'Successfully deleted the portfolio image for this student!');
	}

    // ===== STUDENT ===== //
    // Upload portfolio
	public function student_store_portfolio(Request $request, $course_id){
		$request->validate([
			'file' => [
				'nullable',
				function ($attribute, $value, $fail) {
					if (!$value->isValid()) {
						$fail('Invalid file uploaded.');
					}
		
					$mimeType = $value->getMimeType();
					if (!str_starts_with($mimeType, 'image/') && !str_starts_with($mimeType, 'video/')) {
						$fail('The file must be an image or video.');
					}
				},
			],
			'link' => 'nullable',
		]);

		$paths = [];
		try {
			DB::beginTransaction();

			$course = Course::findOrFail($course_id);
			$student = Auth::user();
			
			if ($request->file('files')) {
				foreach ($request->file('files') as $req_file) {
					$mimeType = $req_file->getMimeType();
					$type = explode('/', $mimeType)[0];
			
					$path = $req_file->store('progress-portfolio');
					$paths[] = $path;
			
					Portfolio::create([
						'student_id' => $student->id,
						'course_id' => $course->id,
						'path' => $path,
						'type' => $type,
					]);
				}
			}
			else if($request->link){
				Portfolio::create([
					'student_id' => $student->id,
					'course_id' => $course->id,
					'path' => $request->link,
					'type' => 'link'
				]);
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			foreach($paths as $p){
				Storage::disk('public')->delete($p);
			}

			return back()->with('danger', 'System failed to upload portfolio images for this student. Please report to our IT team, error detail: ' . $e->getMessage());
		}

		return back()->withQuery(['content' => request('content')])->with('success', 'Successfully uploaded the portfolio files!');
	}
}
