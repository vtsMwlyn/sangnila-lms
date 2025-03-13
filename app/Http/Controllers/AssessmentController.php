<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentController extends Controller
{
    public function create($student_id, $course_id){
        return view('roles.teacher.assessment.create', [
            'student' => User::findOrFail($student_id),
            'course' => Course::findOrFail($course_id),
        ]);
    }

    public function store(Request $request, $student_id, $course_id){
        $validatedData = $request->validate([
            'performance_score' => 'required',
            'performance_description' => 'required',
            'technical_skill_score' => 'required',
            'technical_skill_description' => 'required',
            'aesthetical_skill_score' => 'required',
            'aesthetical_skill_description' => 'required',
            'overall_score' => 'required',
            'overall_description' => 'required',
        ]);

        $course = Course::findOrFail($course_id);
        $student = User::findOrFail($student_id);

        Assessment::create([
            'course_id' => $course->id,
            'student_id' => $student->id,
            'teacher_id' => Auth::user()->id,
            'performance_score' => $validatedData['performance_score'],
            'performance_description' => e($validatedData['performance_description']),
            'technical_skill_score' => $validatedData['technical_skill_score'],
            'technical_skill_description' => e($validatedData['technical_skill_description']),
            'aesthetical_skill_score' => $validatedData['aesthetical_skill_score'],
            'aesthetical_skill_description' => e($validatedData['aesthetical_skill_description']),
            'overall_score' => $validatedData['overall_score'],
            'overall_description' => e($validatedData['overall_description']),
        ]);

        return redirect(route('teacher.student.show', [$student->id, $course->id]))->with('successUploadAssessment', 'Successfully uploaded the assessment for this student!');
    }

    public function edit($assessment_id){
        return view('roles.teacher.assessment.edit', [
            'assessment' => Assessment::findOrFail($assessment_id)
        ]);
    }

    public function update(Request $request, $assessment_id){
        $assessment = Assessment::findOrFail($assessment_id);

        $validatedData = $request->validate([
            'performance_score' => 'required',
            'performance_description' => 'required',
            'technical_skill_score' => 'required',
            'technical_skill_description' => 'required',
            'aesthetical_skill_score' => 'required',
            'aesthetical_skill_description' => 'required',
            'overall_score' => 'required',
            'overall_description' => 'required',
        ]);

        $course = $assessment->course;
        $student = $assessment->student;

        $assessment->update([
            'performance_score' => $validatedData['performance_score'],
            'performance_description' => e($validatedData['performance_description']),
            'technical_skill_score' => $validatedData['technical_skill_score'],
            'technical_skill_description' => e($validatedData['technical_skill_description']),
            'aesthetical_skill_score' => $validatedData['aesthetical_skill_score'],
            'aesthetical_skill_description' => e($validatedData['aesthetical_skill_description']),
            'overall_score' => $validatedData['overall_score'],
            'overall_description' => e($validatedData['overall_description']),
        ]);

        return redirect(route('teacher.student.show', [$student->id, $course->id]))->with('successEditAssessment', 'Successfully edited the assessment for this student!');
    }

    public function admin_change_certificate_access(Request $request, $assessment_id){
        $assessment = Assessment::findOrFail($assessment_id);
        $assessment->update([
            'certificate_accessible' => $request->certificate_access
        ]);

        return back()->with('successChangeCertificateAccess', 'Certificate access changed successfully!');
    }
}
