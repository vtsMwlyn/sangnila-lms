<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\CourseTopic;
use App\Models\Role;
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

        User::create(["email" => "dummyAdmin.sangnila@gmail.com", "full_name" => "Dummy Admin", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 2, "status" => "enabled"]);
		User::create(["email" => "dummyTeacher.sangnila@gmail.com", "full_name" => "Dummy Teacher", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 3, "status" => "enabled"]);
		User::create(["email" => "sussyTeacher.sangnila@gmail.com", "full_name" => "Sussy Teacher", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 3, "status" => "enabled"]);
		User::create(["email" => "dummyStudent.sangnila@gmail.com", "full_name" => "Dummy Student", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 4, "status" => "enabled"]);
		User::create(["email" => "sussyStudent.sangnila@gmail.com", "full_name" => "Sussy Student", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 4, "status" => "enabled"]);
		User::create(["email" => "fakeStudent.sangnila@gmail.com", "full_name" => "Fake Student", "password" => bcrypt("password"), "email_verified_at" => now(), "role_id" => 4, "status" => "enabled"]);

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

		CourseTopic::create(["title" => "TOPIC 01", "course_id" => 3]);
		CourseTopic::create(["title" => "TOPIC 02", "course_id" => 3]);
		CourseTopic::create(["title" => "TOPIC 01", "course_id" => 2]);

		CourseMaterial::create([
			"course_topic_id" => 1,
			"title" => "[TOPIC 01] - Introduction to Concept Art Video",
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
    }
}
