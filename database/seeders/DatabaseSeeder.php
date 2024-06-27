<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Role;
use App\Models\User;
use App\Models\Course;
use App\Models\UserDetail;
use App\Models\Topic;
use App\Models\CourseStudent;
use App\Models\CourseTeacher;
use App\Models\ImportedStudent;
use App\Models\Material;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use App\Models\Progress;
use App\Models\StudentAssignment;
use App\Models\StudentAttendance;
use App\Models\Submission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
	private function newUser($email, $full_name, $role_id, $gender){
		$user = User::create([
			"full_name" => $full_name,
			"email" => $email,
			"password" => Hash::make(trans("strings.default_password")),
			"email_verified_at" => now(),
			"role_id" => $role_id,
			"status" => "enabled"
		]);

		UserDetail::create(["user_id" => $user->id, "gender" => $gender]);
	}

	private function assignStudent($student_name, $teacher_name, $course_name, $max_session){
		$student = User::where("role_id", 3)->where("full_name", $student_name)->first();
		$teacher = User::where("role_id", 2)->where("full_name", $teacher_name)->first();
		$course = Course::where("course_name", $course_name)->first();

		CourseStudent::create([
			"course_id" => $course->id,
			"student_id" => $student->id,
			"teacher_id" => $teacher->id,
			"max_course_session" => $max_session,
			"is_imported" => 0
		]);

		Payment::create([
			"student_id" => $student->id,
			"course_id" => $course->id,
			"number_of_payment" => 1
		]);

		foreach ($course->topics as $index1 => $topic) {
			foreach($topic->materials as $index2 => $material) {
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

				Progress::create($newData);
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

	private function addTopicAndMaterial($course_name, $topic_name, $materials){
		$course = Course::where("course_name", $course_name)->first();
		$topic = Topic::create(["course_id" => $course->id, "title" => $topic_name]);

		foreach($materials as $material){
			Material::create(["topic_id" => $topic->id, "title" => $material, "link" => "https://www.google.com/", "desc" => "This is a description of a material."]);
		}
	}

	private function changeMaterialLink($course_name, $topic_name, $material_name, $new_link){
		$course = Course::where("course_name", $course_name)->first();
		$topic = Topic::where("course_id", $course->id)->where("title", $topic_name)->first();
		$material = Material::where("topic_id", $topic->id)->where("title", $material_name);

		$material->update(["link" => $new_link]);
	}

	private function importAndAssign($new_student_name, $new_student_email, $gender, $course_name, $teacher_name, $max_course_session, $last_attendance_count){
		$newStudent = User::create([
			"full_name" => $new_student_name,
			"email" => $new_student_email,
			"password" => Hash::make(trans("strings.default_password")),
			"email_verified_at" => now(),
			"role_id" => 3,
			"status" => "enabled"
		]);

		UserDetail::create([
			"user_id" => $newStudent->id,
			"gender" => $gender
		]);

		$course = Course::where("course_name", $course_name)->first();
		$teacher = User::where("role_id", 2)->where("full_name", $teacher_name)->first();

		CourseStudent::create([
			"course_id" => $course->id,
			"teacher_id" => $teacher->id,
			"student_id" => $newStudent->id,
			"max_course_session" => $max_course_session,
			"is_imported" => 1
		]);

		ImportedStudent::create([
			"course_id" => $course->id,
			"student_id" => $newStudent->id,
			"last_attendance_count" => $last_attendance_count
		]);
	}

	private function newAssignment($course_name, $teacher_name, $students, $assignment_title, $deadline_date){
		$course = Course::where("course_name", $course_name)->first();
		$teacher = User::where("role_id", 2)->where("full_name", $teacher_name)->first();

		$newAsg = Assignment::create([
			"teacher_id" => $teacher->id,
			"course_id" => $course->id,
			"title" => $assignment_title,
			"desc" => "This is the description of the assignment.",
			"link" => "https://www.google.com/",
			"deadline_date" => $deadline_date,
			"deadline_time" => "23:59:00"
		]);

		foreach($students as $student_name){
			$student = User::where("role_id", 3)->where("full_name", $student_name)->first();
			StudentAssignment::create([
				"student_id" => $student->id,
				"assignment_id" => $newAsg->id
			]);
		}
	}

	public function newAttendance($course_name, $teacher_name, $students_attended, $attendance_date){
		$course = Course::where("course_name", $course_name)->first();
		$teacher = User::where("role_id", 2)->where("full_name", $teacher_name)->first();

		$newAttendance = Attendance::create([
			"teacher_id" => $teacher->id,
			"course_id" =>  $course->id,
			"attendance_date" => $attendance_date,
			"attendance_identifier" => $course->id . "_" . $teacher->id . "/" . round(microtime(true) * 1000)
		]);

		$course_students = CourseStudent::where("course_id", $course->id)->where("teacher_id", $teacher->id)->get();

		foreach($course_students as $cs){
			$is_attend = false;
			foreach($students_attended as $sattend){
				if($cs->student->full_name == $sattend){
					$is_attend = true;
					break;
				}
			}

			$newData = ["user_id" => $cs->student->id, "attendance_id" => $newAttendance->id,];

			if($is_attend){
				$newData["is_attend"] = 1;
				$newData["attendance_detail"] = "Hadir dan telah menyelesaikan materi tertentu pada topic tertentu pada pertemuan kali ini. Kenapa tertentu karena ini adalah fake data yang dibuat pakai seeder biar kelihatan keterangan attendance minimal 30 kata.";
			} else {
				$newData["is_attend"] = 0;
				$newData["attendance_detail"] = "Student sakit/izin/alfa.";
			}

			StudentAttendance::create($newData);
		}
	}

	private function newSubmissions($course_name, $teacher_name, $student_name, $assignment_title, $submissions){
		$course = Course::where("course_name", $course_name)->first();
		$teacher = User::where("role_id", 2)->where("full_name", $teacher_name)->first();
		$student = User::where("role_id", 3)->where("full_name", $student_name)->first();

		foreach($submissions as $submission){
			$assignment = Assignment::where("course_id", $course->id)->where("teacher_id", $teacher->id)->where("title", $assignment_title)->first();

			Submission::create([
				"student_id" => $student->id,
				"assignment_id" => $assignment->id,
				"link" => "https://www.google.com/",
				"title" => $submission,
				"status" => (now() > $assignment->deadline_date . " " . $assignment->deadline_time)? "Late" : "On Time",
				"feedback" => null
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

		$this->newUser("vannestheo.sangnila@gmail.com", "Vannes Theo Sudarsono", 1, 1);
		$this->newUser("immanuelgiovano.sangnila@gmail.com", "Immanuel Giovano", 1, 1);
		$this->newUser("victor.sangnila@gmail.com", "Victor", 1, 1);
		$this->newUser("feby.sangnila@gmail.com", "Feby", 1, 2);
		$this->newUser("tiwi.sangnila@gmail.com", "Pratiwi", 1, 2);
		$this->newUser("iswansudaryo.sangnila@gmail.com", "Iswan Sudaryo", 1, 1);

		$this->newUser("hari.sangnila@gmail.com", "Hari", 2, 1);
		$this->newUser("lgaby.sangnila@gmail.com", "Gaby", 2, 2);
		$this->newUser("iswansudaryo02.sangnila@gmail.com", "Iswan Sudaryo (Teacher)", 2, 1);
		$this->newUser("vincent.sangnila@gmail.com", "Vincent", 2, 1);
		$this->newUser("immanuelgiovano02.sangnila@gmail.com", "Immanuel Giovano (Teacher)", 2, 1);

		/*Hari's students: Digital Drawing, Roblox*/
		$this->newUser("jack.sangnila@gmail.com", "Jack", 3, 1);
		$this->newUser("jillian.sangnila@gmail.com", "Jillian P. Tanuwijaya", 3, 2);
		$this->newUser("jocheli.sangnila@gmail.com", "Jocheli Kensi Budianti", 3, 2);

		$this->newUser("batara.sangnila@gmail.com", "Batara Feodore Setiawan", 3, 1);
		$this->newUser("bellrich.sangnila@gmail.com", "Bellrich Kevin Tjahyadi", 3, 1);
		$this->newUser("benedict.sangnila@gmail.com", "Benedict Jacob", 3, 2);

		/*Gaby's students: Digital Drawing*/
		$this->newUser("melly.sangnila@gmail.com", "Melly Tanto", 3, 2);
		$this->newUser("zhafira.sangnila@gmail.com", "Zhafira Jasmine", 3, 2);
		$this->newUser("vanya.sangnila@gmail.com", "Vanya Farelia", 3, 2);
		$this->newUser("freya.sangnila@gmail.com", "Freya Pramudia", 3, 2);
		$this->newUser("kenzie.sangnila@gmail.com", "Kenzie Gautama Dirgantara", 3, 1);

		/*Iswan's students: 3D Modelling, Concept Art*/
		$this->newUser("louisha.sangnila@gmail.com", "Louisha Annabelle", 3, 2);
		$this->newUser("gayle.sangnila@gmail.com", "Gayle Farrel Patria", 3, 1);
		$this->newUser("angela.sangnila@gmail.com", "Angela Nathania", 3, 2);
		$this->newUser("balya.sangnila@gmail.com", "Balya Malkan Mahyuzar", 3, 1);
		$this->newUser("alvin.sangnila@gmail.com", "Alvin Edward", 3, 1);

		$this->newUser("ethan.sangnila@gmail.com", "Ethan Alexander Irawan", 3, 1);
		$this->newUser("jezriel.sangnila@gmail.com", "Jezriel Connery", 3, 1);
		$this->newUser("martha.sangnila@gmail.com", "Martha Theresia Ramlie", 3, 2);
		$this->newUser("janicelyn.sangnila@gmail.com", "Janicelyn Daviena Godarma", 3, 2);
		$this->newUser("grace.sangnila@gmail.com", "Grace Devana Kusnandar", 3, 2);

		/*Vincent's students: 3D Modelling, 2D Modelling*/
		$this->newUser("philia.sangnila@gmail.com", "Philia Valeraine Alverna", 3, 2);
		$this->newUser("kensi.sangnila@gmail.com", "Kensi Sinclair", 3, 1);
		$this->newUser("giselle.sangnila@gmail.com", "Giselle Saputra", 3, 2);

		//Generate Courses
		Course::create([
			"course_name" => "3D Modelling",
			"course_description" => "This course introduces students to the fundamentals of 3D modeling using industry-standard software. Students will learn to create detailed and realistic 3D models, starting from basic shapes and advancing to complex forms. The course covers topics such as topology, texturing, lighting, and rendering. By the end of the course, students will have a portfolio of 3D models suitable for games, movies, and other digital media."
		]);
		Course::create([
			"course_name" => "Concept Art",
			"course_description" => "This course focuses on the creation of concept art for video games, films, and animation. Students will learn to visualize and design characters, environments, and props, translating ideas from imagination to visual reality. The course covers drawing techniques, color theory, perspective, and digital painting. Students will develop a strong portfolio of concept art that demonstrates their ability to convey stories and ideas visually."
		]);
		Course::create([
			"course_name" => "Digital Drawing",
			"course_description" => "This course explores the techniques and tools used in digital drawing. Students will learn to use digital tablets and software to create illustrations, comics, and other digital artworks. The course covers line work, shading, color, composition, and various styles of digital art. By the end of the course, students will have a diverse portfolio of digital drawings and a solid understanding of the digital art workflow."
		]);
		Course::create([
			"course_name" => "2D Animation",
			"course_description" => "This course teaches the principles and techniques of 2D animation. Students will learn to create animations using traditional and digital methods, focusing on keyframes, in-betweens, timing, and motion. The course covers character animation, lip-syncing, and special effects. Students will produce several short animations and a final project that showcases their animation skills."
		]);
		Course::create([
			"course_name" => "Roblox",
			"course_description" => "This course introduces students to game development using the Roblox platform. Students will learn to design, script, and publish their own games using Roblox Studio and Lua programming language. The course covers game mechanics, user interface design, and multiplayer game features. By the end of the course, students will have created a fully functional Roblox game and gained experience in game development and coding."
		]);
		Course::create([
			"course_name" => "Web Development",
			"course_description" => "This course covers the essentials of web development, from basic HTML and CSS to advanced JavaScript and backend programming. Students will learn to create responsive and interactive websites, focusing on user experience and design principles. The course includes topics such as front-end frameworks, server-side programming, databases, and version control. By the end of the course, students will have built several web projects and have a strong foundation in web development."
		]);


		// ===== Generate Topics and Materials ===== //
		/*Topics for Concept Art*/
		$this->addTopicAndMaterial("Concept Art", "Prop Design", [
			"Additive and Subtractive Shape",
			"Sketching Details",
			"Line Art",
			"Value and Ligthing",
			"Color Theory and Exploration",
			"Material Studies",
			"Orthographic View / Turn Table",
			"Blow Up and Detailing",
			"Submission: Prop Design Portfolio"
		]);
		$this->addTopicAndMaterial("Concept Art", "Interior Environment", [
			"Isometric Perspective: Simple Objects in 3D Space",
			"Isometric Perspective: Complex Objects in 3D Space",
			"Sketch and Detailing",
			"Value and Lighting",
			"Color Exploration",
			"Consultation",
			"Rendering",
			"Consultation",
			"Submission: Interior Environment Portfolio"
		]);

		/*Topics for 3D Modelling*/
		$this->addTopicAndMaterial("3D Modelling", "Intermediate Modelling", [
			"Expand your 3D Modelling by Using Different Tools and Edits"
		]);
		$this->addTopicAndMaterial("3D Modelling", "Product Design Modelling", [
			"Design a Simple Product for Advertising by Using Image Texturing"
		]);
		$this->addTopicAndMaterial("3D Modelling", "Interior Visualization", [
			"Exercise Modelling an Interior Room with Different Types of Objects"
		]);

		/*Topics for 2D Animation*/
		$this->addTopicAndMaterial("2D Animation", "Introduction + Software Practice #1", [
			"Penggunaan Drawing Tools",
			"Penggunaan Deformers",
			"Penggunaan Effects dan Animation",
			"Workflow dan Interface"
		]);
		$this->addTopicAndMaterial("2D Animation", "Software Practice #2", [
			"Simple Animation Using Deformer (Pendulum, Bouncing Ball)",
			"Latihan Menggambar Rough Pose, Clean Up, Detail"
		]);
		$this->addTopicAndMaterial("2D Animation", "Timing #1", [
			"Menggeser Bola",
			"Bouncing Ball",
			"Timing Cepat"
		]);
		$this->addTopicAndMaterial("2D Animation", "Timing #2", [
			"Avoid Tweening: Raising Arms",
			"Avoid Tweening: Jumping",
			"Avoid Tweening: Half Body Turn"
		]);
		$this->addTopicAndMaterial("2D Animation", "Spacing #1", [
			"Head Turn #1",
			"Take #1 (Half Body)"
		]);

		/*Topics for Digital Drawing*/
		// $this->addTopicAndMaterial("Digital Drawing", "Logo Design", [
		// 	"Sketching and Ideation",
		// 	"Blocking and Clean Up",
		// 	"Color Exploration"
		// ]);
		// $this->addTopicAndMaterial("Digital Drawing", "Flora and Fauna Drawing", [
		// 	"Optimize using Mirror",
		// 	"Simetrical Tools to Create Repetition",
		// 	"Export Pattern and Implementation into Drawing"
		// ]);
		// $this->addTopicAndMaterial("Digital Drawing", "Gradient Background", [
		// 	"Sketching and Ideation",
		// 	"Lineart",
		// 	"Color and Shading with Gradients"
		// ]);
		$this->addTopicAndMaterial("Digital Drawing", "Character Design", [
			"Head Construction",
			"Body Construction",
			"Gesture, Ekspresi, dan Tangan",
			"Gesture and Full Body Construction",
			"Take a Reference for Drawing"
		]);
		$this->addTopicAndMaterial("Digital Drawing", "Properties", [
			"Intro to Perspektif",
			"Drawing Boxes (Base Cube)",
			"Drawing Vases (Base Tube)",
			"Drawing Any Still Life Object using Envelope, Cube",
			"Drawing Character with Properties"
		]);
		$this->addTopicAndMaterial("Digital Drawing", "Flora and Fauna", [
			"Drawing Leaves",
			"Drawing Tree",
			"Drawing Flower",
			"Body Structure in Animal",
			"Drawing any Animal"
		]);
		$this->addTopicAndMaterial("Digital Drawing", "Background", [
			"Drawing Living Room in 1 Perspective",
			"Drawing Bed Room in 2 Perspective",
			"Drawing Park",
			"Drawing Character in a Place #1",
			"Drawing Character in a Place #2"
		]);

		/*Topic and material for web development*/
		$this->addTopicAndMaterial("Web Development", "Construct a web page using HTML", [
			"Introduction to HTML",
			"Making simple article web page",
			"Insert media to web page"
		]);
		$this->addTopicAndMaterial("Web Development", "Styling a web page using CSS", [
			"Introduction to CSS",
			"Decorating web page using CSS",
			"Positioning elements using CSS"
		]);
		$this->addTopicAndMaterial("Web Development", "Using JavaScript to control the behavior and events in a web page", [
			"Introduction to JS",
			"Basics of JS",
			"Manipulating HTML content and style",
			"Handling events in a web page",
			"Form validation using JS"
		]);
		$this->addTopicAndMaterial("Web Development", "Using PHP and MySQL to control and handle data from back end side", [
			"Introduction to PHP",
			"Basics of PHP",
			"Retrieving data from forms",
			"Introduction to MySQL",
			"Insert and show data from tables",
			"Update and delete data from tables"
		]);

		// Some example material links
		$this->changeMaterialLink("Digital Drawing", "Character Design", "Head Construction", "https://stanprokopenko.com/2012/08/video-draw-head-angle-1/");
		$this->changeMaterialLink("Digital Drawing", "Character Design", "Body Construction", "https://youtu.be/Lw0nZEw8IIk?si=pMEOKHi1CzI9_bXJ");
		$this->changeMaterialLink("Digital Drawing", "Character Design", "Gesture, Ekspresi, dan Tangan", "https://drive.google.com/file/d/145xGbwJf43Ug58F2rxxmStfmLrWYx3px/view?usp=sharing");
		$this->changeMaterialLink("Digital Drawing", "Character Design", "Gesture and Full Body Construction", "https://drive.google.com/file/d/1f4PVu3pYDRF-Dc_2GejbGnzHz31S4pZK/view?usp=sharing");
		$this->changeMaterialLink("Web Development", "Construct a web page using HTML", "Introduction to HTML", "https://drive.google.com/file/d/1fqf2oSxdLTFjbC99khOpv5DcHTTvi6Xg/view?usp=sharing");
		$this->changeMaterialLink("Web Development", "Styling a web page using CSS", "Introduction to CSS", "https://drive.google.com/file/d/1n_3G4wvHKxNH7u-PQWry8-ocGPcMnt8i/view?usp=sharing");
		$this->changeMaterialLink("Web Development", "Using JavaScript to control the behavior and events in a web page", "Introduction to JS", "https://drive.google.com/file/d/1TM6T69eUs84B1RcEtbUcEMEQwW51LmbK/view?usp=sharing");


		// ===== Assign students to courses + generate progress ===== //
		/*Hari's students*/
		$this->assignStudent("Jack", "Hari", "Digital Drawing", 8);
		$this->assignStudent("Jillian P. Tanuwijaya", "Hari", "Digital Drawing", 8);
		$this->assignStudent("Jocheli Kensi Budianti", "Hari", "Digital Drawing", 8);

		$this->assignStudent("Jack", "Immanuel Giovano (Teacher)", "Web Development", 16);
		$this->assignStudent("Jillian P. Tanuwijaya", "Immanuel Giovano (Teacher)", "Web Development", 16);
		$this->assignStudent("Jocheli Kensi Budianti", "Immanuel Giovano (Teacher)", "Web Development", 16);

		$this->assignStudent("Batara Feodore Setiawan","Hari", "Roblox", 8);
		$this->assignStudent("Bellrich Kevin Tjahyadi","Hari", "Roblox", 8);
		$this->assignStudent("Benedict Jacob","Hari", "Roblox", 8);

		/*Gaby's students*/
		$this->assignStudent("Melly Tanto", "Gaby", "Digital Drawing", 16);
		$this->assignStudent("Zhafira Jasmine", "Gaby", "Digital Drawing", 16);
		$this->assignStudent("Vanya Farelia", "Gaby", "Digital Drawing", 16);
		$this->assignStudent("Freya Pramudia", "Gaby", "Digital Drawing", 16);
		$this->assignStudent("Kenzie Gautama Dirgantara", "Gaby", "Digital Drawing", 16);

		/*Iswan's students*/
		$this->assignStudent("Louisha Annabelle", "Iswan Sudaryo (Teacher)", "3D Modelling", 8);
		$this->assignStudent("Gayle Farrel Patria", "Iswan Sudaryo (Teacher)", "3D Modelling", 8);
		$this->assignStudent("Angela Nathania", "Iswan Sudaryo (Teacher)", "3D Modelling", 8);
		$this->assignStudent("Balya Malkan Mahyuzar", "Iswan Sudaryo (Teacher)", "3D Modelling", 8);
		$this->assignStudent("Alvin Edward", "Iswan Sudaryo (Teacher)", "3D Modelling", 8);

		$this->assignStudent("Ethan Alexander Irawan", "Iswan Sudaryo (Teacher)", "Concept Art", 8);
		$this->assignStudent("Jezriel Connery", "Iswan Sudaryo (Teacher)", "Concept Art", 8);
		$this->assignStudent("Martha Theresia Ramlie", "Iswan Sudaryo (Teacher)", "Concept Art", 8);
		$this->assignStudent("Janicelyn Daviena Godarma", "Iswan Sudaryo (Teacher)", "Concept Art", 8);
		$this->assignStudent("Grace Devana Kusnandar", "Iswan Sudaryo (Teacher)", "Concept Art", 8);

		/*Vincent's students*/
		$this->assignStudent("Philia Valeraine Alverna", "Vincent", "3D Modelling", 24);
		$this->assignStudent("Kensi Sinclair", "Vincent", "3D Modelling", 24);
		$this->assignStudent("Giselle Saputra", "Vincent", "3D Modelling", 24);

		$this->importAndAssign("Melissa", "melissa.sangnila@gmail.com", 2, "2D Animation", "Vincent", 16, 15);
		$this->importAndAssign("Chelsea", "chelsea.sangnila@gmail.com", 2, "2D Animation", "Vincent", 8, 4);
		$this->importAndAssign("Kenzo", "kenzo.sangnila@gmail.com", 2, "2D Animation", "Vincent", 8, 7);

		// Assign teachers to courses
		$this->assignTeacher("Hari", ["Digital Drawing", "Roblox"]);
		$this->assignTeacher("Gaby", ["Digital Drawing"]);
		$this->assignTeacher("Iswan Sudaryo (Teacher)", ["3D Modelling", "Concept Art"]);
		$this->assignTeacher("Vincent", ["3D Modelling", "2D Animation"]);
		$this->assignTeacher("Immanuel Giovano (Teacher)", ["Web Development"]);


		// ===== Generate Assignments ===== //
		/* For Iswan Sudaryo (Teacher)'s Students */
		$this->newAssignment("3D Modelling", "Iswan Sudaryo (Teacher)", ["Louisha Annabelle", "Gayle Farrel Patria", "Angela Nathania", "Balya Malkan Mahyuzar", "Alvin Edward"], "Assignment 1 3D Modelling", "2024-06-20");
		$this->newAssignment("3D Modelling", "Iswan Sudaryo (Teacher)", ["Louisha Annabelle", "Gayle Farrel Patria", "Angela Nathania", "Balya Malkan Mahyuzar", "Alvin Edward"], "Assignment 2 3D Modelling", "2025-06-27");

		$this->newAssignment("Concept Art", "Iswan Sudaryo (Teacher)", ["Ethan Alexander Irawan", "Jezriel Connery", "Martha Theresia Ramlie", "Janicelyn Daviena Godarma", "Grace Devana Kusnandar"], "Assignment 1 Concept Art", "2024-06-21");
		$this->newAssignment("Concept Art", "Iswan Sudaryo (Teacher)", ["Ethan Alexander Irawan", "Jezriel Connery", "Martha Theresia Ramlie", "Janicelyn Daviena Godarma", "Grace Devana Kusnandar"], "Assignment 2 Concept Art", "2025-06-28");

		/* For Vincent's Students */
		$this->newAssignment("3D Modelling", "Vincent", ["Philia Valeraine Alverna", "Kensi Sinclair", "Giselle Saputra"], "Assignment 1 3D Modelling", "2024-06-18");
		$this->newAssignment("3D Modelling", "Vincent", ["Philia Valeraine Alverna", "Kensi Sinclair", "Giselle Saputra"], "Assignment 2 3D Modelling", "2025-06-26");

		$this->newAssignment("2D Animation", "Vincent", ["Melissa", "Chelsea", "Kenzo"], "Assignment 1 2D Animation", "2024-06-10");
		$this->newAssignment("2D Animation", "Vincent", ["Melissa", "Chelsea", "Kenzo"], "Assignment 2 2D Animation", "2025-06-17");
		$this->newAssignment("2D Animation", "Vincent", ["Melissa", "Chelsea", "Kenzo"], "Assignment 3 2D Animation", "2026-06-24");

		/* For Hari's Students */
		$this->newAssignment("Digital Drawing", "Hari", ["Jack", "Jillian P. Tanuwijaya", "Jocheli Kensi Budianti"], "Assignment 1 Digital Drawing", "2024-06-15");
		$this->newAssignment("Digital Drawing", "Hari", ["Jack", "Jillian P. Tanuwijaya", "Jocheli Kensi Budianti"], "Assignment 2 Digital Drawing", "2025-06-22");

		$this->newAssignment("Roblox", "Hari", ["Batara Feodore Setiawan", "Bellrich Kevin Tjahyadi", "Benedict Jacob"], "Assignment 1 Roblox", "2024-06-30");
		$this->newAssignment("Roblox", "Hari", ["Batara Feodore Setiawan", "Bellrich Kevin Tjahyadi", "Benedict Jacob"], "Assignment 2 Roblox", "2025-07-07");

		/* For Gaby's Students */
		$this->newAssignment("Digital Drawing", "Gaby", ["Melly Tanto", "Zhafira Jasmine", "Vanya Farelia", "Freya Pramudia", "Kenzie Gautama Dirgantara"], "Assignment 1 Digital Drawing", "2024-06-01");
		$this->newAssignment("Digital Drawing", "Gaby", ["Melly Tanto", "Zhafira Jasmine", "Vanya Farelia", "Freya Pramudia", "Kenzie Gautama Dirgantara"], "Assignment 2 Digital Drawing", "2025-06-08");
		$this->newAssignment("Digital Drawing", "Gaby", ["Melly Tanto", "Zhafira Jasmine", "Vanya Farelia", "Freya Pramudia", "Kenzie Gautama Dirgantara"], "Assignment 3 Digital Drawing", "2026-06-15");

		/* For Immanuel Giovano (Teacher)'s Students */
		$this->newAssignment("Web Development", "Immanuel Giovano (Teacher)", ["Jack", "Jillian P. Tanuwijaya", "Jocheli Kensi Budianti"], "Assignment 1 Web Development", "2024-06-09");
		$this->newAssignment("Web Development", "Immanuel Giovano (Teacher)", ["Jack", "Jillian P. Tanuwijaya", "Jocheli Kensi Budianti"], "Assignment 2 Web Development", "2025-06-16");
		$this->newAssignment("Web Development", "Immanuel Giovano (Teacher)", ["Jack", "Jillian P. Tanuwijaya", "Jocheli Kensi Budianti"], "Assignment 3 Web Development", "2026-06-23");
		$this->newAssignment("Web Development", "Immanuel Giovano (Teacher)", ["Jack", "Jillian P. Tanuwijaya", "Jocheli Kensi Budianti"], "Assignment 4 Web Development", "2027-06-30");


		// ===== Generate Attendances Data ===== //
		/* For Iswan Sudaryo (Teacher)'s Students */
		$this->newAttendance("3D Modelling", "Iswan Sudaryo (Teacher)", ["Louisha Annabelle", "Gayle Farrel Patria", "Angela Nathania", "Balya Malkan Mahyuzar", "Alvin Edward"], "2024-06-20");
		$this->newAttendance("3D Modelling", "Iswan Sudaryo (Teacher)", ["Gayle Farrel Patria", "Angela Nathania", "Balya Malkan Mahyuzar", "Alvin Edward"], "2024-06-27");

		$this->newAttendance("Concept Art", "Iswan Sudaryo (Teacher)", ["Martha Theresia Ramlie", "Janicelyn Daviena Godarma", "Grace Devana Kusnandar"], "2024-06-21");

		/* For Vincent's Students */
		$this->newAttendance("3D Modelling", "Vincent", ["Philia Valeraine Alverna", "Kensi Sinclair", "Giselle Saputra"], "2024-06-18");
		$this->newAttendance("3D Modelling", "Vincent", ["Philia Valeraine Alverna", "Kensi Sinclair", "Giselle Saputra"], "2025-06-26");

		$this->newAttendance("2D Animation", "Vincent", ["Chelsea", "Kenzo"], "2024-06-10");

		/* For Hari's Students */
		$this->newAttendance("Digital Drawing", "Hari", ["Jack", "Jillian P. Tanuwijaya", "Jocheli Kensi Budianti"], "2024-06-15");
		$this->newAttendance("Digital Drawing", "Hari", ["Jack", "Jillian P. Tanuwijaya", "Jocheli Kensi Budianti"], "2024-06-22");
		$this->newAttendance("Digital Drawing", "Hari", ["Jack", "Jocheli Kensi Budianti"], "2024-06-29");
		$this->newAttendance("Digital Drawing", "Hari", ["Jack", "Jillian P. Tanuwijaya", "Jocheli Kensi Budianti"], "2024-07-06");
		$this->newAttendance("Digital Drawing", "Hari", ["Jack", "Jillian P. Tanuwijaya", "Jocheli Kensi Budianti"], "2024-07-13");
		$this->newAttendance("Digital Drawing", "Hari", ["Jack", "Jillian P. Tanuwijaya", "Jocheli Kensi Budianti"], "2024-07-20");
		$this->newAttendance("Digital Drawing", "Hari", ["Jocheli Kensi Budianti"], "2024-07-27");
		$this->newAttendance("Digital Drawing", "Hari", ["Jillian P. Tanuwijaya"], "2024-08-03");

		/* For Gaby's Students */
		$this->newAttendance("Digital Drawing", "Gaby", ["Melly Tanto", "Zhafira Jasmine", "Vanya Farelia", "Freya Pramudia", "Kenzie Gautama Dirgantara"], "2024-06-01");

		/* For Immanuel Giovano (Teacher)'s Students */
		$this->newAttendance("Web Development", "Immanuel Giovano (Teacher)", ["Jack", "Jillian P. Tanuwijaya"], "2024-06-09");
		$this->newAttendance("Web Development", "Immanuel Giovano (Teacher)", ["Jack", "Jocheli Kensi Budianti"], "2025-06-16");

		// ===== GENERATE SUBMISSIONS ===== //
		/* Submissions for assignments in Hari's courses */
		$this->newSubmissions("Digital Drawing", "Hari", "Jack", "Assignment 1 Digital Drawing", ["Submission Assignment 1 Digital Drawing - Jack", "Revision Assignment 1 Digital Drawing - Jack"]);
		$this->newSubmissions("Digital Drawing", "Hari", "Jillian P. Tanuwijaya", "Assignment 1 Digital Drawing", ["Submission Assignment 1 Digital Drawing - Jillian P. Tanuwijaya"]);
		$this->newSubmissions("Digital Drawing", "Hari", "Jocheli Kensi Budianti", "Assignment 1 Digital Drawing", ["Submission 1 Assignment 1 Digital Drawing - Jocheli Kensi Budianti", "Submission 2 Assignment 1 Digital Drawing - Jocheli Kensi Budianti", "Submission 3 Assignment 1 Digital Drawing - Jocheli Kensi Budianti"]);

		$this->newSubmissions("Digital Drawing", "Hari", "Jack", "Assignment 2 Digital Drawing", ["Submission Assignment 2 Digital Drawing - Jack"]);
		$this->newSubmissions("Digital Drawing", "Hari", "Jillian P. Tanuwijaya", "Assignment 2 Digital Drawing", ["Submission Assignment 2 Digital Drawing - Jillian P. Tanuwijaya", "Revision Assignment 2 Digital Drawing - Jillian P. Tanuwijaya"]);

		/* Submissions for assignments in Iswan Sudaryo (Teacher)'s courses */
		$this->newSubmissions("Concept Art", "Iswan Sudaryo (Teacher)", "Ethan Alexander Irawan", "Assignment 1 Concept Art", ["Submission Assignment 1 Concept Art - Ethan Alexander Irawan", "Revision Assignment 1 Concept Art - Ethan Alexander Irawan"]);
		$this->newSubmissions("Concept Art", "Iswan Sudaryo (Teacher)", "Jezriel Connery", "Assignment 1 Concept Art", ["Submission Assignment 1 Concept Art - Jezriel Connery", "Revision Assignment 1 Concept Art - Jezriel Connery"]);
		$this->newSubmissions("Concept Art", "Iswan Sudaryo (Teacher)", "Martha Theresia Ramlie", "Assignment 1 Concept Art", ["Submission Assignment 1 Concept Art - Martha Theresia Ramlie"]);
		$this->newSubmissions("Concept Art", "Iswan Sudaryo (Teacher)", "Janicelyn Daviena Godarma", "Assignment 1 Concept Art", ["Submission Assignment 1 Concept Art - Janicelyn Daviena Godarma", "Revision Assignment 1 Concept Art - Janicelyn Daviena Godarma"]);
		$this->newSubmissions("Concept Art", "Iswan Sudaryo (Teacher)", "Grace Devana Kusnandar", "Assignment 1 Concept Art", ["Submission Assignment 1 Concept Art - Grace Devana Kusnandar"]);

		$this->newSubmissions("Concept Art", "Iswan Sudaryo (Teacher)", "Jezriel Connery", "Assignment 2 Concept Art", ["Submission Assignment 2 Concept Art - Jezriel Connery"]);
		$this->newSubmissions("Concept Art", "Iswan Sudaryo (Teacher)", "Janicelyn Daviena Godarma", "Assignment 2 Concept Art", ["Submission Assignment 2 Concept Art - Janicelyn Daviena Godarma", "Revision Assignment 2 Concept Art - Janicelyn Daviena Godarma"]);
		$this->newSubmissions("Concept Art", "Iswan Sudaryo (Teacher)", "Grace Devana Kusnandar", "Assignment 2 Concept Art", ["Submission Assignment 2 Concept Art - Grace Devana Kusnandar", "Revision Assignment 2 Concept Art - Grace Devana Kusnandar"]);

		/* Submissions for assignments in Vincent's courses */
		$this->newSubmissions("3D Modelling", "Vincent", "Philia Valeraine Alverna", "Assignment 1 3D Modelling", ["Submission Assignment 1 3D Modelling - Philia Valeraine Alverna", "Revision Assignment 1 3D Modelling - Philia Valeraine Alverna"]);
		$this->newSubmissions("3D Modelling", "Vincent", "Kensi Sinclair", "Assignment 1 3D Modelling", ["Submission Assignment 1 3D Modelling - Kensi Sinclair", "Revision Assignment 1 3D Modelling - Kensi Sinclair"]);
		$this->newSubmissions("3D Modelling", "Vincent", "Giselle Saputra", "Assignment 1 3D Modelling", ["Submission 1 Assignment 1 3D Modelling - Giselle Saputra", "Submission 2 Assignment 1 3D Modelling - Giselle Saputra", "Revision Assignment 1 3D Modelling - Giselle Saputra"]);

		/* Submissions for assignments in Gaby's courses */
		$this->newSubmissions("Digital Drawing", "Gaby", "Melly Tanto", "Assignment 1 Digital Drawing", ["Submission Assignment 1 Digital Drawing - Melly Tanto"]);
		$this->newSubmissions("Digital Drawing", "Gaby", "Zhafira Jasmine", "Assignment 1 Digital Drawing", ["Submission Assignment 1 Digital Drawing - Zhafira Jasmine", "Revision Assignment 1 Digital Drawing - Zhafira Jasmine"]);
		$this->newSubmissions("Digital Drawing", "Gaby", "Freya Pramudia", "Assignment 1 Digital Drawing", ["Submission Assignment 1 Digital Drawing - Freya Pramudia"]);

		$this->newSubmissions("Digital Drawing", "Gaby", "Melly Tanto", "Assignment 2 Digital Drawing", ["Submission Assignment 2 Digital Drawing - Melly Tanto", "Revision Assignment 2 Digital Drawing - Melly Tanto"]);
		$this->newSubmissions("Digital Drawing", "Gaby", "Zhafira Jasmine", "Assignment 2 Digital Drawing", ["Submission Assignment 2 Digital Drawing - Zhafira Jasmine"]);
		$this->newSubmissions("Digital Drawing", "Gaby", "Vanya Farelia", "Assignment 2 Digital Drawing", ["Submission Assignment 2 Digital Drawing - Vanya Farelia", "Revision Assignment 2 Digital Drawing - Vanya Farelia"]);
		$this->newSubmissions("Digital Drawing", "Gaby", "Freya Pramudia", "Assignment 2 Digital Drawing", ["Submission Assignment 2 Digital Drawing - Freya Pramudia"]);

		$this->newSubmissions("Digital Drawing", "Gaby", "Vanya Farelia", "Assignment 3 Digital Drawing", ["Submission Assignment 3 Digital Drawing - Vanya Farelia", "Revision Assignment 3 Digital Drawing - Vanya Farelia"]);
		$this->newSubmissions("Digital Drawing", "Gaby", "Freya Pramudia", "Assignment 3 Digital Drawing", ["Submission Assignment 3 Digital Drawing - Freya Pramudia"]);
	}
}
