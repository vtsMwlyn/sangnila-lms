<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Activity;
use App\Models\Progress;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\CourseStudent;
use App\Models\StudentAttendance;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class CustomOperationController extends Controller
{
    public function generate_progress_for_all_student(){
		try {
			DB::beginTransaction();
			
			foreach(User::where('role_id', 3)->get() as $student){
				foreach($student->enrolled_courses as $course){
					$teacher = CourseStudent::where('course_id', $course->id)->where('student_id', $student->id)->first()->teacher;
	
					$activities = Activity::whereHas('topic', function($query) use ($course, $teacher){
						return $query->where('course_id', $course->id)->where('user_id', $teacher->id);
					})->orderBy('session', 'asc')->get();
	
					$student_attendances = StudentAttendance::where('student_id', $student->id)->whereHas('attendance', function($query) use ($course){
						return $query->where('course_id', $course->id);
					})->get();
			
					$session_counter = 1;
	
					foreach($activities as $index => $activity){
						if($activity->session != $session_counter){
							$session_counter++;
						}
	
						Progress::updateOrCreate([
							"student_id" => $student->id,
							"course_id" => $course->id,
							"activity_id" => $activity->id,
						],
						[
							"status" => $session_counter <= $student_attendances->count() + 1 || $index == 0? 'unlocked' : 'locked',
						]);
					}
				}
				
			}

			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();

			throw $e;
		}
	}

    public function sync_syllabus_for_all_course(){
		try {
			DB::beginTransaction();
	
			foreach(CourseTeacher::all() as $ct){
				$course = $ct->course;
				$teacher = $ct->teacher;

				Topic::where("course_id", $course->id)->where("user_id", Auth::user()->id)->delete();
	
				$curriculum_topics = CurriculumTopic::where("course_id", $course->id)->get();
		
				foreach($curriculum_topics as $ctopic){
					$ntopic = Topic::create([
						"title" => $ctopic->title,
						"user_id" => Auth::user()->id,
						"course_id" => $course->id
					]);
		
					foreach($ctopic->curriculum_activities as $activity){
						$nyuu = Activity::create([
							"topic_id" => $ntopic->id,
							"title" => $activity->title,
							"desc" => e($activity->desc),
							"link" => $activity->link,
							"session" => $activity->session
						]);
		
						foreach($activity->learning_outcomes as $ctlo){
							LearningOutcomeActivity::create([
								"learning_outcome_id" => $ctlo->id,
								"activity_id" => $nyuu->id
							]);
						}
					}
				}
				
				// Auto unlock and update student progress
				$activities = Activity::whereHas('topic', function($query) use ($course, $teacher){
					return $query->where('course_id', $course->id)->where('user_id', $teacher->id);
				})->orderBy('session', 'asc')->get();
		
				foreach(CourseStudent::where('teacher_id', $teacher->id)->where('course_id', $course->id)->get() as $cs){
					$student = $cs->student;
		
					$student_attendances = StudentAttendance::where('student_id', $student->id)->whereHas('attendance', function($query) use ($course){
						return $query->where('course_id', $course->id);
					})->get();
		
					$session_counter = 1;
		
					foreach($activities as $index => $activity){
						if($activity->session != $session_counter){
							$session_counter++;
						}
		
						Progress::create([
							"student_id" => $student->id,
							"activity_id" => $activity->id,
							"course_id" => $course->id,
							"status" => $session_counter <= $student_attendances->count() || $index == 0? 'unlocked' : 'locked',
						]);
					}
				}
			}
	
			DB::commit();
		}
		catch(Exception $e){
			DB::rollback();
	
			throw $e;
		}
	}

    public function move_attendances_data_to_new_course(){
        $student_attendances = StudentAttendance::whereIn('student_id', [176, 174, 102, 74])
        ->whereHas('attendance', function ($query) {
            $query->where('course_id', 3);
        })
        ->with('attendance')
        ->get();

        try {
            DB::beginTransaction();

            foreach($student_attendances as $sa){
                $oldAttendance = $sa->attendance;
                $atdDate = $oldAttendance->attendance_date;
    
                $existingAttendance = Attendance::where('course_id', 50)->where('attendance_date', $atdDate)->first();
    
                if($existingAttendance){
                    $sa->attendance_id = $existingAttendance->id;
                    $sa->save();
                }
                else {
                    $newAtd = Attendance::create([
                        'course_id' => 50, //50 is the targetted course
                        'uploader_id' => 181, //181 is sysadmin
                        'attendance_date' => $atdDate
                    ]);
    
                    $sa->attendance_id = $newAtd->id;
                    $sa->save();
                }
            }

            DB::commit();

            return 'done';
        }
        catch(Exception $e){
            DB::rollback();

            throw $e;
        }
    }
}
