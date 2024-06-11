<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Course;
use App\Models\UserDetail;
use App\Models\Topic;
use App\Models\CourseStudent;
use App\Models\CourseTeacher;
use App\Models\Material;
use Illuminate\Database\Seeder;
use App\Models\Progress;

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

	private function assignStudent($student_name, $teacher_name, $courses, $max_session){
		$student = User::where("role_id", 3)->where("full_name", $student_name)->first();
		$teacher = User::where("role_id", 2)->where("full_name", $teacher_name)->first();

		foreach($courses as $c){
			$course = Course::where("course_name", $c)->first();

			CourseStudent::create([
				"course_id" => $course->id,
				"student_id" => $student->id,
				"teacher_id" => $teacher->id,
				"max_course_session" => $max_session
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
		$this->newUser("iswansudaryo.sangnila@gmail.com", "Iswan Sudaryo", 1);

		$this->newUser("hari.sangnila@gmail.com", "Hari", 2);
		$this->newUser("lgaby.sangnila@gmail.com", "Gaby", 2);
		$this->newUser("iswansudaryo.teacher.sangnila@gmail.com", "Iswan Sudaryo (Teacher)", 2);
		$this->newUser("vincent.sangnila@gmail.com", "Vincent", 2);
		$this->newUser("immanuelgiovano.teacher.sangnila@gmail.com", "Immanuel Giovano (Teacher)", 2);

		/*Hari's students: Digital Drawing, Roblox*/
		$this->newUser("jack.sangnila@gmail.com", "Jack", 3);
		$this->newUser("jillian.sangnila@gmail.com", "Jillian P. Tanuwijaya", 3);
		$this->newUser("jocheli.sangnila@gmail.com", "Jocheli Kensi Budianti", 3);

		$this->newUser("batara.sangnila@gmail.com", "Batara Feodore Setiawan", 3);
		$this->newUser("bellrich.sangnila@gmail.com", "Bellrich Kevin Tjahyadi", 3);
		$this->newUser("benedict.sangnila@gmail.com", "Benedict Jacob", 3);

		/*Gaby's students: Digital Drawing*/
		$this->newUser("melly.sangnila@gmail.com", "Melly Tanto", 3);
		$this->newUser("zhafira.sangnila@gmail.com", "Zhafira Jasmine", 3);
		$this->newUser("vanya.sangnila@gmail.com", "Vanya Farelia", 3);
		$this->newUser("freya.sangnila@gmail.com", "Freya Pramudia", 3);
		$this->newUser("kenzie.sangnila@gmail.com", "Kenzie Gautama Dirgantara", 3);

		/*Iswan's students: 3D Modelling, Concept Art*/
		$this->newUser("louisha.sangnila@gmail.com", "Louisha Annabelle", 3);
		$this->newUser("gayle.sangnila@gmail.com", "Gayle Farrel Patria", 3);
		$this->newUser("angela.sangnila@gmail.com", "Angela Nathania", 3);
		$this->newUser("balya.sangnila@gmail.com", "Balya Malkan Mahyuzar", 3);
		$this->newUser("alvin.sangnila@gmail.com", "Alvin Edward", 3);

		$this->newUser("ethan.sangnila@gmail.com", "Ethan Alexander Irawan", 3);
		$this->newUser("jezriel.sangnila@gmail.com", "Jezriel Connery", 3);
		$this->newUser("martha.sangnila@gmail.com", "Martha Theresia Ramlie", 3);
		$this->newUser("janicelyn.sangnila@gmail.com", "Janicelyn Daviena Godarma", 3);
		$this->newUser("grace.sangnila@gmail.com", "Grace Devana Kusnandar", 3);

		/*Vincent's students: 3D Modelling, 2D Modelling*/
		$this->newUser("philia.sangnila@gmail.com", "Philia Valeraine Alverna", 3);
		$this->newUser("kensi.sangnila@gmail.com", "Kensi Sinclair", 3);
		$this->newUser("giselle.sangnila@gmail.com", "Giselle Saputra", 3);

		//Generate Courses
		Course::create([
			"course_name" => "3D Modelling",
			"course_description" => "This course serves as a comprehensive introduction to the exciting world of 3D modeling. Whether you're a beginner looking to delve into the realm of digital design or an enthusiast seeking to enhance your skills, this course provides a solid foundation in the principles and techniques of 3D modeling.  Throughout the course, students will explore the fundamental concepts of 3D modeling, learning how to create three-dimensional objects and environments using industry-standard software tools."
		]);
		Course::create([
			"course_name" => "Concept Art",
			"course_description" => "This is the description of course Concept Art. You can modify or add anything here."
		]);
		Course::create([
			"course_name" => "Digital Drawing",
			"course_description" => "This is the description of course Digital Drawing. You can modify or add anything here."
		]);
		Course::create([
			"course_name" => "2D Animation",
			"course_description" => "This is the description of course 2D Animation. You can modify or add anything here."
		]);
		Course::create([
			"course_name" => "Roblox",
			"course_description" => "This is the description of course Roblox. You can modify or add anything here."
		]);
		Course::create([
			"course_name" => "Web Development",
			"course_description" => "Learn to build websites using HTML, CSS, JS, PHP, and MySQL."
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


		// ===== Assign students to courses + generate progress ===== //
		/*Hari's students*/
		$this->assignStudent("Jack", "Hari", ["Digital Drawing"], 20);
		$this->assignStudent("Jillian P. Tanuwijaya", "Hari", ["Digital Drawing"], 20);
		$this->assignStudent("Jocheli Kensi Budianti", "Hari", ["Digital Drawing"], 20);

		$this->assignStudent("Batara Feodore Setiawan","Hari", ["Roblox"], 20);
		$this->assignStudent("Bellrich Kevin Tjahyadi","Hari", ["Roblox"], 20);
		$this->assignStudent("Benedict Jacob","Hari", ["Roblox"], 20);

		/*Gaby's students*/
		$this->assignStudent("Melly Tanto", "Gaby", ["Digital Drawing"], 20);
		$this->assignStudent("Zhafira Jasmine", "Gaby", ["Digital Drawing"], 20);
		$this->assignStudent("Vanya Farelia", "Gaby", ["Digital Drawing"], 20);
		$this->assignStudent("Freya Pramudia", "Gaby", ["Digital Drawing"], 20);
		$this->assignStudent("Kenzie Gautama Dirgantara", "Gaby", ["Digital Drawing"], 20);

		/*Iswan's students*/
		$this->assignStudent("Louisha Annabelle", "Iswan Sudaryo (Teacher)", ["3D Modelling"], 20);
		$this->assignStudent("Gayle Farrel Patria", "Iswan Sudaryo (Teacher)", ["3D Modelling"], 20);
		$this->assignStudent("Angela Nathania", "Iswan Sudaryo (Teacher)", ["3D Modelling"], 20);
		$this->assignStudent("Balya Malkan Mahyuzar", "Iswan Sudaryo (Teacher)", ["3D Modelling"], 20);
		$this->assignStudent("Alvin Edward", "Iswan Sudaryo (Teacher)", ["3D Modelling"], 20);

		$this->assignStudent("Ethan Alexander Irawan", "Iswan Sudaryo (Teacher)", ["Concept Art"], 20);
		$this->assignStudent("Jezriel Connery", "Iswan Sudaryo (Teacher)", ["Concept Art"], 20);
		$this->assignStudent("Martha Theresia Ramlie", "Iswan Sudaryo (Teacher)", ["Concept Art"], 20);
		$this->assignStudent("Janicelyn Daviena Godarma", "Iswan Sudaryo (Teacher)", ["Concept Art"], 20);
		$this->assignStudent("Grace Devana Kusnandar", "Iswan Sudaryo (Teacher)", ["Concept Art"], 20);

		/*Vincent's students*/
		$this->assignStudent("Philia Valeraine Alverna", "Vincent", ["3D Modelling"], 20);
		$this->assignStudent("Kensi Sinclair", "Vincent", ["3D Modelling"], 20);
		$this->assignStudent("Giselle Saputra", "Vincent", ["3D Modelling"], 20);


		// Assign teachers to courses
		$this->assignTeacher("Hari", ["Digital Drawing", "Roblox"]);
		$this->assignTeacher("Gaby", ["Digital Drawing"]);
		$this->assignTeacher("Iswan Sudaryo (Teacher)", ["3D Modelling", "Concept Art"]);
		$this->assignTeacher("Vincent", ["3D Modelling", "2D Animation"]);
		$this->assignTeacher("Immanuel Giovano (Teacher)", ["Web Development"]);
	}
}
