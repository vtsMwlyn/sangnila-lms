<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

require_once __DIR__ . "/Helpers.php";

class CurriculumsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // For 3D Modelling
		generateCurriculum("3D Modelling", "Intermediate Modelling", [
			"Expand your 3D Modelling by Using Different Tools and Edits"
		]);
		generateCurriculum("3D Modelling", "Product Design Modelling", [
			"Design a Simple Product for Advertising by Using Image Texturing"
		]);
		generateCurriculum("3D Modelling", "Interior Visualization", [
			"Exercise Modelling an Interior Room with Different Types of Objects"
		]);

		// For Digital Drawing
		generateCurriculum("Digital Drawing", "Character Design", [
			"Head Construction",
			"Body Construction",
			"Gesture, Ekspresi, dan Tangan",
			"Gesture and Full Body Construction",
			"Take a Reference for Drawing"
		]);
		generateCurriculum("Digital Drawing", "Properties", [
			"Intro to Perspektif",
			"Drawing Boxes (Base Cube)",
			"Drawing Vases (Base Tube)",
			"Drawing Any Still Life Object using Envelope, Cube",
			"Drawing Character with Properties"
		]);
		generateCurriculum("Digital Drawing", "Flora and Fauna", [
			"Drawing Leaves",
			"Drawing Tree",
			"Drawing Flower",
			"Body Structure in Animal",
			"Drawing any Animal"
		]);
		generateCurriculum("Digital Drawing", "Background", [
			"Drawing Living Room in 1 Perspective",
			"Drawing Bed Room in 2 Perspective",
			"Drawing Park",
			"Drawing Character in a Place #1",
			"Drawing Character in a Place #2"
		]);

		// For Concept Art
		generateCurriculum("Concept Art", "Prop Design", [
			"Additive and Subtractive Shape",
			"Sketching Details",
			"Line Art",
			"Value and Ligthing",
			"Color Theory and Exploration",
			"Activity Studies",
			"Orthographic View / Turn Table",
			"Blow Up and Detailing",
			"Submission: Prop Design Portfolio"
		]);
		generateCurriculum("Concept Art", "Interior Environment", [
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

		// For Web Development
		generateCurriculum("Web Development", "Construct a web page using HTML", [
			"Introduction to HTML",
			"Making simple article web page",
			"Insert media to web page"
		]);
		generateCurriculum("Web Development", "Styling a web page using CSS", [
			"Introduction to CSS",
			"Decorating web page using CSS",
			"Positioning elements using CSS"
		]);
		generateCurriculum("Web Development", "Using JavaScript to control the behavior and events in a web page", [
			"Introduction to JS",
			"Basics of JS",
			"Manipulating HTML content and style",
			"Handling events in a web page",
			"Form validation using JS"
		]);
		generateCurriculum("Web Development", "Using PHP and MySQL to control and handle data from back end side", [
			"Introduction to PHP",
			"Basics of PHP",
			"Retrieving data from forms",
			"Introduction to MySQL",
			"Insert and show data from tables",
			"Update and delete data from tables"
		]);

		// For Roblox
		generateCurriculum("Roblox", "Introduction to Roblox", [
			"Introduction and software installation",
			"Software features and tools",
			"Gather idea for your own game"
		]);

		generateCurriculum("Roblox", "Basic Programming", [
			"Introduction to programming",
			"Learn to solve simple problems using given utilities",
			"Making a simple game"
		]);
    }
}
