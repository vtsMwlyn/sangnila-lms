<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

require_once __DIR__ . "/Helpers.php";

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
    }
}
