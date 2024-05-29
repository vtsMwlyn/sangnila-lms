<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\CourseStudent;
use App\Models\CourseTeacher;
use App\Models\CourseTopic;
use App\Models\Role;
use App\Models\StudentAssignment;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
		//Generate Users and UserDetails
		Role::create(["role_name" => "Admin"]);
		Role::create(["role_name" => "Teacher"]);
		Role::create(["role_name" => "Student"]);
		// Role::create(["role_name" => "Parent"]); //postponed

        User::create(["email" => "admin.sangnila@gmail.com", "full_name" => "Admin", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 1, "status" => "enabled"]); UserDetail::create(["user_id" => 1]);

		/*COMMENT BELOW COMMANDS TO SET THE APP DATA TO COMPLETELY EMPTY*/
		User::create(["email" => "hari.sangnila@gmail.com", "full_name" => "Hari", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 2, "status" => "enabled"]); UserDetail::create(["user_id" => 2]);
		User::create(["email" => "benita.sangnila@gmail.com", "full_name" => "Benita", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 2, "status" => "enabled"]); UserDetail::create(["user_id" => 3]);
		User::create(["email" => "emily.sangnila@gmail.com", "full_name" => "Emily", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 2, "status" => "enabled"]); UserDetail::create(["user_id" => 4]);

		User::create(["email" => "jack.sangnila@gmail.com", "full_name" => "Jack", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 3, "status" => "enabled"]); UserDetail::create(["user_id" => 5]);
		User::create(["email" => "jillian.sangnila@gmail.com", "full_name" => "Jillian P. Tanuwijaya", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 3, "status" => "enabled"]); UserDetail::create(["user_id" => 6]);
		User::create(["email" => "jocheli.sangnila@gmail.com", "full_name" => "Jocheli Kensi Budianti", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 3, "status" => "enabled"]); UserDetail::create(["user_id" => 7]);
		User::create(["email" => "bellrich.sangnila@gmail.com", "full_name" => "Bellrich Kevin Tjahyadi", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 3, "status" => "enabled"]); UserDetail::create(["user_id" => 8]);
		User::create(["email" => "batara.sangnila@gmail.com", "full_name" => "Batara Feodore Setiawan", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 3, "status" => "enabled"]); UserDetail::create(["user_id" => 9]);
		User::create(["email" => "benedict.sangnila@gmail.com", "full_name" => "Benedict Jacob", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 3, "status" => "enabled"]); UserDetail::create(["user_id" => 10]);

		User::create(["email" => "vannestheo.sangnila@gmail.com", "full_name" => "Vannes Theo Sudarsono", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 1, "status" => "enabled"]); UserDetail::create(["user_id" => 11]);
		User::create(["email" => "immanuelgiovano.sangnila@gmail.com", "full_name" => "Immanuel Giovano", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 1, "status" => "enabled"]); UserDetail::create(["user_id" => 12]);
		User::create(["email" => "jessica.sangnila@gmail.com", "full_name" => "Jessica", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 1, "status" => "enabled"]); UserDetail::create(["user_id" => 13]);
		User::create(["email" => "victor.sangnila@gmail.com", "full_name" => "Victor", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 1, "status" => "enabled"]); UserDetail::create(["user_id" => 14]);
		User::create(["email" => "feby.sangnila@gmail.com", "full_name" => "Feby", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 1, "status" => "enabled"]); UserDetail::create(["user_id" => 15]);
		User::create(["email" => "pratiwi.sangnila@gmail.com", "full_name" => "Pratiwi", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 1, "status" => "enabled"]); UserDetail::create(["user_id" => 16]);

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

		//Generate Course Assignments to Teachers and Students
		CourseTeacher::create(["course_id" => 2, "user_id" => 2]);
		CourseTeacher::create(["course_id" => 4, "user_id" => 2]);
		CourseTeacher::create(["course_id" => 1, "user_id" => 3]);
		CourseTeacher::create(["course_id" => 3, "user_id" => 4]);

		CourseStudent::create(["course_id" => 2, "user_id" => 5, "max_course_session" => 15]);
		CourseStudent::create(["course_id" => 2, "user_id" => 6, "max_course_session" => 20]);
		CourseStudent::create(["course_id" => 2, "user_id" => 7, "max_course_session" => 20]);
		CourseStudent::create(["course_id" => 4, "user_id" => 8, "max_course_session" => 15]);
		CourseStudent::create(["course_id" => 4, "user_id" => 9, "max_course_session" => 30]);
		CourseStudent::create(["course_id" => 4, "user_id" => 10, "max_course_session" => 40]);
    }
}
