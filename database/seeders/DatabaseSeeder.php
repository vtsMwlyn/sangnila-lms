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
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
		Role::create(["role_name" => "SysAdmin"]);
		Role::create(["role_name" => "Admin"]);
		Role::create(["role_name" => "Teacher"]);
		Role::create(["role_name" => "Student"]);
		Role::create(["role_name" => "Parent"]);

        User::create(["email" => "admin.sangnila@gmail.com", "full_name" => "Admin", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 2, "status" => "enabled"]);
		User::create(["email" => "teacherA.sangnila@gmail.com", "full_name" => "Teacher A", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 3, "status" => "enabled"]);
		User::create(["email" => "teacherB.sangnila@gmail.com", "full_name" => "Teacher B", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 3, "status" => "enabled"]);
		User::create(["email" => "studentA.sangnila@gmail.com", "full_name" => "Student A", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 4, "status" => "enabled"]);
		User::create(["email" => "studentB.sangnila@gmail.com", "full_name" => "Student B", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 4, "status" => "enabled"]);
		User::create(["email" => "studentC.sangnila@gmail.com", "full_name" => "Student C", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 4, "status" => "enabled"]);
		User::create(["email" => "studentD.sangnila@gmail.com", "full_name" => "Student D", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 4, "status" => "enabled"]);

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

		CourseTopic::create(["title" => "TOPIC 01 - Introduction to Concept Art", "course_id" => 3]);
		CourseTopic::create(["title" => "TOPIC 02 - Software Installation and Test Run", "course_id" => 3]);
		CourseTopic::create(["title" => "TOPIC 01 - Get Started with Roblox", "course_id" => 2]);
		CourseTopic::create(["title" => "[TOPIC 01] 3D Modelling Fundamentals", "course_id" => 1]);
		CourseTopic::create(["title" => "[TOPIC 02] 3D Drawing Basics Techniques", "course_id" => 1]);
		CourseTopic::create(["title" => "[TOPIC 03] 3D Drawing Advanced Techniques", "course_id" => 1]);

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
		CourseMaterial::create([
			"course_topic_id" => 3,
			"title" => "Introduction to Roblox studio video",
			"link" => "https://youtu.be/UMmZMPYAzZs"
		]);
		CourseMaterial::create([
			"course_topic_id" => 3,
			"title" => "Roblox studio article",
			"link" => "https://create.roblox.com/docs/tutorials/first-experience"
		]);
		CourseMaterial::create([
			"course_topic_id" => 4,
			"title" => "Get to know what is 3D modelling",
			"link" => "https://www.futurelearn.com/info/blog/general/what-is-3d-modelling"
		]);
		CourseMaterial::create([
			"course_topic_id" => 5,
			"title" => "Learn to draw 3D objects",
			"link" => "https://youtu.be/48_P5552638?si=ysyeVlA38_9v6CQY"
		]);
		CourseMaterial::create([
			"course_topic_id" => 6,
			"title" => "More advanced techniques in 3D drawing",
			"link" => "https://youtu.be/PqysfuKMQbM?si=rwyVnJV7jhcI3wa-"
		]);

		CourseTeacher::create(["course_id" => 1, "user_id" => 2]);
		CourseTeacher::create(["course_id" => 2, "user_id" => 2]);
		CourseTeacher::create(["course_id" => 2, "user_id" => 3]);
		CourseTeacher::create(["course_id" => 3, "user_id" => 3]);

		CourseStudent::create(["course_id" => 1, "user_id" => 4]);
		CourseStudent::create(["course_id" => 1, "user_id" => 5]);
		CourseStudent::create(["course_id" => 1, "user_id" => 6]);
		CourseStudent::create(["course_id" => 2, "user_id" => 4]);
		CourseStudent::create(["course_id" => 2, "user_id" => 5]);
		CourseStudent::create(["course_id" => 3, "user_id" => 5]);
		CourseStudent::create(["course_id" => 3, "user_id" => 6]);
		CourseStudent::create(["course_id" => 3, "user_id" => 7]);

		StudentAssignment::create([
			"teacher_id" => 2, "course_id" => 1, "student_id" => 4, "student_is_assigned" => 1,
			"title" => "Introduction to 3D Modelling",
			"desc" => "Write summary of what have you learned from 3D modelling introduction in minimum of 1 A4 page",
			"link" => "https://www.google.com/", "deadline_date" => date("y-m-d"), "deadline_time" => "23:59:00"
		]);
		StudentAssignment::create([
			"teacher_id" => 2, "course_id" => 1, "student_id" => 5, "student_is_assigned" => 0,
			"title" => "Introduction to 3D Modelling",
			"desc" => "Write summary of what have you learned from 3D modelling introduction in minimum of 1 A4 page",
			"link" => "https://www.google.com/", "deadline_date" => date("y-m-d"), "deadline_time" => "23:59:00"
		]);
		StudentAssignment::create([
			"teacher_id" => 2, "course_id" => 1, "student_id" => 6, "student_is_assigned" => 1,
			"title" => "Introduction to 3D Modelling",
			"desc" => "Write summary of what have you learned from 3D modelling introduction in minimum of 1 A4 page",
			"link" => "https://www.google.com/", "deadline_date" => date("y-m-d"), "deadline_time" => "23:59:00"
		]);
		StudentAssignment::create([
			"teacher_id" => 2, "course_id" => 2, "student_id" => 4, "student_is_assigned" => 1,
			"title" => "Design Your Own Game Idea",
			"desc" => "Think, imagine, and design your own game! Please write the concept with some image illustration that describe your game.",
			"link" => "https://www.google.com/", "deadline_date" => date("y-m-d"), "deadline_time" => "23:59:00"
		]);
		StudentAssignment::create([
			"teacher_id" => 2, "course_id" => 2, "student_id" => 5, "student_is_assigned" => 1,
			"title" => "Design Your Own Game Idea",
			"desc" => "Think, imagine, and design your own game! Please write the concept with some image illustration that describe your game.",
			"link" => "https://www.google.com/", "deadline_date" => date("y-m-d"), "deadline_time" => "23:59:00"
		]);
		StudentAssignment::create([
			"teacher_id" => 3, "course_id" => 3, "student_id" => 5, "student_is_assigned" => 1,
			"title" => "Software Installation and Test Run",
			"desc" => "Please follow the instruction given in the link then submit screenshots that shows if the software runs well in your device (put the screenshots in a microsoft word file)",
			"link" => "https://www.google.com/", "deadline_date" => date("y-m-d"), "deadline_time" => "23:59:00"
		]);
		StudentAssignment::create([
			"teacher_id" => 3, "course_id" => 3, "student_id" => 6, "student_is_assigned" => 0,
			"title" => "Software Installation and Test Run",
			"desc" => "Please follow the instruction given in the link then submit screenshots that shows if the software runs well in your device (put the screenshots in a microsoft word file)",
			"link" => "https://www.google.com/", "deadline_date" => date("y-m-d"), "deadline_time" => "23:59:00"
		]);
		StudentAssignment::create([
			"teacher_id" => 3, "course_id" => 3, "student_id" => 7, "student_is_assigned" => 0,
			"title" => "Software Installation and Test Run",
			"desc" => "Please follow the instruction given in the link then submit screenshots that shows if the software runs well in your device (put the screenshots in a microsoft word file)",
			"link" => "https://www.google.com/", "deadline_date" => date("y-m-d"), "deadline_time" => "23:59:00"
		]);
    }
}
