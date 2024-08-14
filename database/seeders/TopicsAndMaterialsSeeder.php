<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

require_once __DIR__ . "/Helpers.php";

class TopicsAndMaterialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*Topics for Concept Art*/
		addTopicAndMaterial("Concept Art", "Prop Design", [
			"Additive and Subtractive Shape",
			"Sketching Details",
			"Line Art",
			"Value and Ligthing",
			"Color Theory and Exploration",
			"Material Studies",
			"Orthographic View / Turn Table",
			"Blow Up and Detailing",
			"Submission: Prop Design Portfolio"
		], "Iswan Sudaryo (Teacher)");
		addTopicAndMaterial("Concept Art", "Interior Environment", [
			"Isometric Perspective: Simple Objects in 3D Space",
			"Isometric Perspective: Complex Objects in 3D Space",
			"Sketch and Detailing",
			"Value and Lighting",
			"Color Exploration",
			"Consultation",
			"Rendering",
			"Consultation",
			"Submission: Interior Environment Portfolio"
		], "Iswan Sudaryo (Teacher)");

		/*Topics for 3D Modelling*/
		addTopicAndMaterial("3D Modelling", "Intermediate Modelling", [
			"Expand your 3D Modelling by Using Different Tools and Edits"
		], "Vincent");
		addTopicAndMaterial("3D Modelling", "Product Design Modelling", [
			"Design a Simple Product for Advertising by Using Image Texturing"
		], "Vincent");
		addTopicAndMaterial("3D Modelling", "Interior Visualization", [
			"Exercise Modelling an Interior Room with Different Types of Objects"
		], "Vincent");

		addTopicAndMaterial("3D Modelling", "Intermediate Modelling", [
			"Expand your 3D Modelling by Using Different Tools and Edits"
		], "Iswan Sudaryo (Teacher)");
		addTopicAndMaterial("3D Modelling", "Product Design Modelling", [
			"Design a Simple Product for Advertising by Using Image Texturing"
		], "Iswan Sudaryo (Teacher)");
		addTopicAndMaterial("3D Modelling", "Interior Visualization", [
			"Exercise Modelling an Interior Room with Different Types of Objects"
		], "Iswan Sudaryo (Teacher)");

		/*Topics for 2D Animation*/
		addTopicAndMaterial("2D Animation", "Introduction + Software Practice #1", [
			"Penggunaan Drawing Tools",
			"Penggunaan Deformers",
			"Penggunaan Effects dan Animation",
			"Workflow dan Interface"
		], "Vincent");
		addTopicAndMaterial("2D Animation", "Software Practice #2", [
			"Simple Animation Using Deformer (Pendulum, Bouncing Ball)",
			"Latihan Menggambar Rough Pose, Clean Up, Detail"
		], "Vincent");
		addTopicAndMaterial("2D Animation", "Timing #1", [
			"Menggeser Bola",
			"Bouncing Ball",
			"Timing Cepat"
		], "Vincent");
		addTopicAndMaterial("2D Animation", "Timing #2", [
			"Avoid Tweening: Raising Arms",
			"Avoid Tweening: Jumping",
			"Avoid Tweening: Half Body Turn"
		], "Vincent");
		addTopicAndMaterial("2D Animation", "Spacing #1", [
			"Head Turn #1",
			"Take #1 (Half Body)"
		], "Vincent");

		/*Topics for Digital Drawing*/
		addTopicAndMaterial("Digital Drawing", "Logo Design", [
			"Sketching and Ideation",
			"Blocking and Clean Up",
			"Color Exploration"
		], "Gaby");
		addTopicAndMaterial("Digital Drawing", "Flora and Fauna Drawing", [
			"Optimize using Mirror",
			"Simetrical Tools to Create Repetition",
			"Export Pattern and Implementation into Drawing"
		], "Gaby");
		addTopicAndMaterial("Digital Drawing", "Gradient Background", [
			"Sketching and Ideation",
			"Lineart",
			"Color and Shading with Gradients"
		], "Gaby");

		addTopicAndMaterial("Digital Drawing", "Character Design", [
			"Head Construction",
			"Body Construction",
			"Gesture, Ekspresi, dan Tangan",
			"Gesture and Full Body Construction",
			"Take a Reference for Drawing"
		], "Hari");
		addTopicAndMaterial("Digital Drawing", "Properties", [
			"Intro to Perspektif",
			"Drawing Boxes (Base Cube)",
			"Drawing Vases (Base Tube)",
			"Drawing Any Still Life Object using Envelope, Cube",
			"Drawing Character with Properties"
		], "Hari");
		addTopicAndMaterial("Digital Drawing", "Flora and Fauna", [
			"Drawing Leaves",
			"Drawing Tree",
			"Drawing Flower",
			"Body Structure in Animal",
			"Drawing any Animal"
		], "Hari");
		addTopicAndMaterial("Digital Drawing", "Background", [
			"Drawing Living Room in 1 Perspective",
			"Drawing Bed Room in 2 Perspective",
			"Drawing Park",
			"Drawing Character in a Place #1",
			"Drawing Character in a Place #2"
		], "Hari");

		/*Topic and material for web development*/
		addTopicAndMaterial("Web Development", "Construct a web page using HTML", [
			"Introduction to HTML",
			"Making simple article web page",
			"Insert media to web page"
		], "Immanuel Giovano (Teacher)");
		addTopicAndMaterial("Web Development", "Styling a web page using CSS", [
			"Introduction to CSS",
			"Decorating web page using CSS",
			"Positioning elements using CSS"
		], "Immanuel Giovano (Teacher)");
		addTopicAndMaterial("Web Development", "Using JavaScript to control the behavior and events in a web page", [
			"Introduction to JS",
			"Basics of JS",
			"Manipulating HTML content and style",
			"Handling events in a web page",
			"Form validation using JS"
		], "Immanuel Giovano (Teacher)");
		addTopicAndMaterial("Web Development", "Using PHP and MySQL to control and handle data from back end side", [
			"Introduction to PHP",
			"Basics of PHP",
			"Retrieving data from forms",
			"Introduction to MySQL",
			"Insert and show data from tables",
			"Update and delete data from tables"
		], "Immanuel Giovano (Teacher)");

		// Some example material links
		changeMaterialLink("Digital Drawing", "Character Design", "Head Construction", "https://stanprokopenko.com/2012/08/video-draw-head-angle-1/");
		changeMaterialLink("Digital Drawing", "Character Design", "Body Construction", "https://youtu.be/Lw0nZEw8IIk?si=pMEOKHi1CzI9_bXJ");
		changeMaterialLink("Digital Drawing", "Character Design", "Gesture, Ekspresi, dan Tangan", "https://drive.google.com/file/d/145xGbwJf43Ug58F2rxxmStfmLrWYx3px/view?usp=sharing");
		changeMaterialLink("Digital Drawing", "Character Design", "Gesture and Full Body Construction", "https://drive.google.com/file/d/1f4PVu3pYDRF-Dc_2GejbGnzHz31S4pZK/view?usp=sharing");
		changeMaterialLink("Web Development", "Construct a web page using HTML", "Introduction to HTML", "https://drive.google.com/file/d/1fqf2oSxdLTFjbC99khOpv5DcHTTvi6Xg/view?usp=sharing");
		changeMaterialLink("Web Development", "Styling a web page using CSS", "Introduction to CSS", "https://drive.google.com/file/d/1n_3G4wvHKxNH7u-PQWry8-ocGPcMnt8i/view?usp=sharing");
		changeMaterialLink("Web Development", "Using JavaScript to control the behavior and events in a web page", "Introduction to JS", "https://drive.google.com/file/d/1TM6T69eUs84B1RcEtbUcEMEQwW51LmbK/view?usp=sharing");
    }
}
