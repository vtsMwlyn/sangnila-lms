<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseMaterial;
use Illuminate\Http\Request;

class MaterialController extends Controller {
	// ========== Teacher ==========
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function teacher_index() {
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function teacher_create($course_id) {
		$course = Course::findOrFail($course_id);
		return view('roles.teacher.material.create', [
			'course' => $course
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function teacher_store(Request $request, $course_id) {
		$material = CourseMaterial::create([
			'course_id' => $course_id,
			'title' => $request->title,
			'link' => $request->link
		]);
		return redirect(route('teacher.mycourse.show', $course_id));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function teacher_show($id) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function teacher_edit($material_id) {
		$material = CourseMaterial::findOrFail($material_id);
		return view('roles.teacher.material.edit', [
			'material' => $material
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function teacher_update(Request $request, $id) {
		$material = CourseMaterial::findOrFail($id);
		$data = $request->except(['_token', '_method']);
		CourseMaterial::findOrFail($id)->update($data);
		return redirect(route('teacher.mycourse.show', $material->course->id));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function teacher_destroy($id) {
		//
	}
}
