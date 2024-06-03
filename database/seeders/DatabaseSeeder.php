<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Course;
use App\Models\UserDetail;
use App\Models\CourseTopic;
use App\Models\CourseStudent;
use App\Models\CourseTeacher;
use App\Models\CourseMaterial;
use Illuminate\Database\Seeder;
use App\Models\MaterialProgress;
use App\Models\StudentAssignment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
	private function newUser($email, $full_name, $role_id){
		$user = User::create([
			"full_name" => $full_name,
			"email" => $email,
			"password" => bcrypt("password"),
			"email_verified_at" => now(),
			"role_id" => $role_id,
			"status" => "enabled"
		]);

		UserDetail::create(["user_id" => $user->id]);
	}

	private function assignStudent($student_name, $courses, $max_session){
		$student = User::where("role_id", 3)->where("full_name", $student_name)->first();

		foreach($courses as $c){
			$course = Course::where("course_name", $c)->first();

			CourseStudent::create([
				"course_id" => $course->id,
				"user_id" => $student->id,
				"max_course_session" => $max_session
			]);

			foreach ($course->course_topics as $index1 => $topic) {
				foreach($topic->course_materials as $index2 => $material) {
					$newData = [
						'student_id' => $student->id,
						'material_id' => $material->id,
						'course_id' => $course->id,
					];

					if($index1 == 0 && $index2 == 0){
						$newData['status'] = 'unlocked';
					} else {
						$newData['status'] = 'locked';
					}

					MaterialProgress::create($newData);
				}
			}
		}
	}

	private function assignTeacher($teacher_name, $courses){
		$teacher = User::where("role_id", 2)->where("full_name", $teacher_name)->first();

		foreach($courses as $c){
			$course = Course::where("course_name", $c)->first();

			CourseTeacher::create([
				"user_id" => $teacher->id,
				"course_id" => $course->id
			]);
		}
	}

    public function run()
    {
		//Generate Users and UserDetails
		Role::create(["role_name" => "Admin"]);
		Role::create(["role_name" => "Teacher"]);
		Role::create(["role_name" => "Student"]);
		// Role::create(["role_name" => "Parent"]); //postponed

		// /*COMMENT BELOW COMMANDS TO SET THE APP DATA TO COMPLETELY EMPTY*/

		$this->newUser("vannestheo.sangnila@gmail.com", "Vannes Theo Sudarsono", 1);
		$this->newUser("immanuelgiovano.sangnila@gmail.com", "Immanuel Giovano", 1);
		$this->newUser("jessica.sangnila@gmail.com", "Jessica", 1);
		$this->newUser("victor.sangnila@gmail.com", "Victor", 1);
		$this->newUser("feby.sangnila@gmail.com", "Feby", 1);
		$this->newUser("tiwi.sangnila@gmail.com", "Pratiwi", 1);

		$this->newUser("hari.sangnila@gmail.com", "Hari", 2);
		$this->newUser("lgaby.sangnila@gmail.com", "Gaby", 2);
		$this->newUser("iswansudaryo.sangnila@gmail.com", "Iswan Sudaryo", 2);
		$this->newUser("vincent.sangnila@gmail.com", "Vincent", 2);

		/*Hari's students: Digital Drawing, Roblox*/
		$this->newUser("jack.sangnila@gmail.com", "Jack", 3);
		$this->newUser("jillian.sangnila@gmail.com", "Jillian P. Tanuwijaya", 3);
		$this->newUser("jocheli.sangnila@gmail.com", "Jocheli Kensi Budianti", 3);

		$this->newUser("bellrich.sangnila@gmail.com", "Bellrich Kevin Tjahyadi", 3);
		$this->newUser("batara.sangnila@gmail.com", "Batara Feodore Setiawan", 3);
		$this->newUser("benedict.sangnila@gmail.com", "Benedict Jacob", 3);

		/*Gaby's students: Digital Drawing*/
		$this->newUser("melly.sangnila@gmail.com", "Melly Tanto", 3);
		$this->newUser("zhafira.sangnila@gmail.com", "Zhafira Jasmine", 3);
		$this->newUser("vanya.sangnila@gmail.com", "Vanya Farelia", 3);

		/*Iswan's students: 3D Modelling*/
		$this->newUser("louisha.sangnila@gmail.com", "Louisha Annabelle", 3);
		$this->newUser("gayle.sangnila@gmail.com", "Gayle Farrel Patria", 3);
		$this->newUser("angela.sangnila@gmail.com", "Angela Nathania", 3);
		$this->newUser("balya.sangnila@gmail.com", "Balya Malkan Mahyuzar", 3);
		$this->newUser("alvin.sangnila@gmail.com", "Alvin Edward", 3);

		/*Vincent's students: 3D Modelling*/
		$this->newUser("philia.sangnila@gmail.com", "Philia Valeraine Alverna", 3);
		$this->newUser("kensi.sangnila@gmail.com", "Kensi Sinclair", 3);
		$this->newUser("giselle.sangnila@gmail.com", "Giselle Saputra", 3);

		//Generate Courses
		Course::create([
			"course_name" => "3D Modelling",
			"course_description" => "This course serves as a comprehensive introduction to the exciting world of 3D modeling. Whether you're a beginner looking to delve into the realm of digital design or an enthusiast seeking to enhance your skills, this course provides a solid foundation in the principles and techniques of 3D modeling.  Throughout the course, students will explore the fundamental concepts of 3D modeling, learning how to create three-dimensional objects and environments using industry-standard software tools."
		]);
		Course::create([
			"course_name" => "Roblox",
			"course_description" => "Learn to create various of games using Roblox game engine, designed for easily understandable by kids. It's a good opportunity to learn game development with Roblox before getting started with more advanced game development technologies such as Unity and Unreal game engine."
		]);
		Course::create([
			"course_name" => "Concept Art",
			"course_description" => "This is the description of course Concept Art. You can modify or add anything here."
		]);
		Course::create([
			"course_name" => "Digital Drawing",
			"course_description" => "This is the description of course Digital Drawing. You can modify or add anything here."
		]);


		//Generate Topics
		CourseTopic::create(["title" => "TOPIC 01 - Introduction to Concept Art", "course_id" => 3]);
		CourseTopic::create(["title" => "TOPIC 02 - Software Installation and Test Run", "course_id" => 3]);

		CourseTopic::create(["title" => "Software Intro 101", "course_id" => 2]);
		CourseTopic::create(["title" => "Properties Drawing", "course_id" => 2]);

		CourseTopic::create(["title" => "[TOPIC 01] 3D Modelling Fundamentals", "course_id" => 1]);
		CourseTopic::create(["title" => "[TOPIC 02] 3D Drawing Basics Techniques", "course_id" => 1]);
		CourseTopic::create(["title" => "[TOPIC 03] 3D Drawing Advanced Techniques", "course_id" => 1]);

		CourseTopic::create(["title" => "Character Design", "course_id" => 4]);
		CourseTopic::create(["title" => "Properties", "course_id" => 4]);


		//Generate Materials
		/*Materials for Concept Art*/
		CourseMaterial::create([
			"course_topic_id" => 1,
			"title" => "Introduction to Concept Art Video",
			"link" => "https://youtu.be/61mkx_OV61s"
		]);
		CourseMaterial::create([
			"course_topic_id" => 1,
			"title" => "Introduction to Concept Art Article",
			"link" => "https://www.nfi.edu/concept-art/"
		]);

		CourseMaterial::create([
			"course_topic_id" => 2,
			"title" => "Creating your first concept art video",
			"link" => "https://youtu.be/WVPwtsQ-RzI"
		]);

		/*Materials for Roblox*/
		CourseMaterial::create([
			"course_topic_id" => 3,
			"title" => "Tool intoduction: Brush, Eraser Canvas, Layer",
			"link" => "https://www.google.com/"
		]);
		CourseMaterial::create([
			"course_topic_id" => 3,
			"title" => "How to make basic shape, How to Use Gradient",
			"link" => "https://www.google.com/"
		]);

		CourseMaterial::create([
			"course_topic_id" => 4,
			"title" => "Line art",
			"link" => "https://www.google.com/"
		]);
		CourseMaterial::create([
			"course_topic_id" => 4,
			"title" => "Fill Color Object",
			"link" => "https://www.google.com/"
		]);
		CourseMaterial::create([
			"course_topic_id" => 4,
			"title" => "Put basic shadow and Lighting",
			"link" => "https://www.google.com/"
		]);

		/*Materials for 3D Modelling*/
		CourseMaterial::create([
			"course_topic_id" => 5,
			"title" => "Get to know what is 3D modelling",
			"link" => "https://www.futurelearn.com/info/blog/general/what-is-3d-modelling"
		]);
		CourseMaterial::create([
			"course_topic_id" => 6,
			"title" => "Learn to draw 3D objects",
			"link" => "https://youtu.be/48_P5552638?si=ysyeVlA38_9v6CQY"
		]);
		CourseMaterial::create([
			"course_topic_id" => 7,
			"title" => "More advanced techniques in 3D drawing",
			"link" => "https://youtu.be/PqysfuKMQbM?si=rwyVnJV7jhcI3wa-"
		]);

		/*Materials for Digital Drawing*/
		CourseMaterial::create([
			"course_topic_id" => 8,
			"title" => "Head construction",
			"link" => "https://www.google.com/"
		]);
		CourseMaterial::create([
			"course_topic_id" => 8,
			"title" => "Body construction",
			"link" => "https://www.google.com/"
		]);
		CourseMaterial::create([
			"course_topic_id" => 8,
			"title" => "Gesture, ekspresi, dan tangan",
			"link" => "https://www.google.com/"
		]);

		CourseMaterial::create([
			"course_topic_id" => 9,
			"title" => "Intro to Perspektif",
			"link" => "https://www.google.com/"
		]);
		CourseMaterial::create([
			"course_topic_id" => 9,
			"title" => "Drawing boxes, (base cube)",
			"link" => "https://www.google.com/"
		]);


		// Assign students to courses
		$this->assignStudent("Jack", ["Digital Drawing"], 20);
		$this->assignStudent("Jillian P. Tanuwijaya", ["Digital Drawing"], 20);
		$this->assignStudent("Jocheli Kensi Budianti", ["Digital Drawing"], 20);

		$this->assignStudent("Bellrich Kevin Tjahyadi", ["Roblox"], 20);
		$this->assignStudent("Batara Feodore Setiawan", ["Roblox"], 20);
		$this->assignStudent("Benedict Jacob", ["Roblox"], 20);

		$this->assignStudent("Melly Tanto", ["Digital Drawing"], 20);
		$this->assignStudent("Zhafira Jasmine", ["Digital Drawing"], 20);
		$this->assignStudent("Vanya Farelia", ["Digital Drawing"], 20);

		$this->assignStudent("Louisha Annabelle", ["3D Modelling"], 20);
		$this->assignStudent("Gayle Farrel Patria", ["3D Modelling"], 20);
		$this->assignStudent("Angela Nathania", ["3D Modelling"], 20);
		$this->assignStudent("Balya Malkan Mahyuzar", ["3D Modelling"], 20);
		$this->assignStudent("Alvin Edward", ["3D Modelling"], 20);

		$this->assignStudent("Philia Valeraine Alverna", ["3D Modelling"], 20);
		$this->assignStudent("Kensi Sinclair", ["3D Modelling"], 20);
		$this->assignStudent("Giselle Saputra", ["3D Modelling"], 20);


		// Assign teachers to courses
		$this->assignTeacher("Hari", ["Digital Drawing", "Roblox"]);
		$this->assignTeacher("Gaby", ["Digital Drawing"]);
		$this->assignTeacher("Iswan Sudaryo", ["3D Modelling"]);
		$this->assignTeacher("Vincent", ["3D Modelling"]);
    }
}
