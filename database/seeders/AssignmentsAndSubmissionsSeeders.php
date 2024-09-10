<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

require_once __DIR__ . "/Helpers.php";

class AssignmentsAndSubmissionsSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* For Iswan Sudaryo's Students */
		newAssignment("3D Modelling", "Iswan Sudaryo", ["Louisha Annabelle", "Gayle Farrel Patria", "Angela Nathania", "Balya Malkan Mahyuzar", "Alvin Edward"], "Assignment 1 3D Modelling", "2024-06-20");
		newAssignment("3D Modelling", "Iswan Sudaryo", ["Louisha Annabelle", "Gayle Farrel Patria", "Angela Nathania", "Balya Malkan Mahyuzar", "Alvin Edward"], "Assignment 2 3D Modelling", "2025-06-27");

		newAssignment("Concept Art", "Iswan Sudaryo", ["Ethan Alexander Irawan", "Jezriel Connery", "Martha Theresia Ramlie", "Janicelyn Daviena Godarma", "Grace Devana Kusnandar"], "Assignment 1 Concept Art", "2024-06-21");
		newAssignment("Concept Art", "Iswan Sudaryo", ["Ethan Alexander Irawan", "Jezriel Connery", "Martha Theresia Ramlie", "Janicelyn Daviena Godarma", "Grace Devana Kusnandar"], "Assignment 2 Concept Art", "2025-06-28");

		/* For Vincent's Students */
		newAssignment("3D Modelling", "Vincent", ["Philia Valeraine Alverna", "Kensi Sinclair", "Giselle Saputra"], "Assignment 1 3D Modelling", "2024-06-18");
		newAssignment("3D Modelling", "Vincent", ["Philia Valeraine Alverna", "Kensi Sinclair", "Giselle Saputra"], "Assignment 2 3D Modelling", "2025-06-26");

		newAssignment("2D Animation", "Vincent", ["Chelsea", "Kenzo"], "Assignment 1 2D Animation", "2024-06-10");
		newAssignment("2D Animation", "Vincent", ["Chelsea", "Kenzo"], "Assignment 2 2D Animation", "2025-06-17");
		newAssignment("2D Animation", "Vincent", ["Ken", "Chelsea", "Kenzo"], "Assignment 3 2D Animation", "2026-06-24");

		/* For Hari's Students */
		newAssignment("Digital Drawing", "Hari", ["Jack", "Jillian P. Tanuwijaya", "Jocheli Kensi Budianti"], "Assignment 1 Digital Drawing", "2024-06-15");
		newAssignment("Digital Drawing", "Hari", ["Jack", "Jillian P. Tanuwijaya", "Jocheli Kensi Budianti"], "Assignment 2 Digital Drawing", "2025-06-22");

		newAssignment("Roblox", "Hari", ["Batara Feodore Setiawan", "Bellrich Kevin Tjahyadi", "Benedict Jacob"], "Assignment 1 Roblox", "2024-06-30");
		newAssignment("Roblox", "Hari", ["Batara Feodore Setiawan", "Bellrich Kevin Tjahyadi", "Benedict Jacob"], "Assignment 2 Roblox", "2025-07-07");

		/* For Gaby's Students */
		newAssignment("Digital Drawing", "Gaby", ["Melly Tanto", "Zhafira Jasmine", "Vanya Farelia", "Freya Pramudia", "Kenzie Gautama Dirgantara"], "Assignment 1 Digital Drawing", "2024-06-01");
		newAssignment("Digital Drawing", "Gaby", ["Melly Tanto", "Zhafira Jasmine", "Vanya Farelia", "Freya Pramudia", "Kenzie Gautama Dirgantara"], "Assignment 2 Digital Drawing", "2025-06-08");
		newAssignment("Digital Drawing", "Gaby", ["Melly Tanto", "Zhafira Jasmine", "Vanya Farelia", "Freya Pramudia", "Kenzie Gautama Dirgantara"], "Assignment 3 Digital Drawing", "2026-06-15");

		/* For Immanuel Giovano's Students */
		newAssignment("Web Development", "Immanuel Giovano", ["Jack", "Jillian P. Tanuwijaya", "Jocheli Kensi Budianti"], "Assignment 1 Web Development", "2024-06-09");
		newAssignment("Web Development", "Immanuel Giovano", ["Jack", "Jillian P. Tanuwijaya", "Jocheli Kensi Budianti"], "Assignment 2 Web Development", "2025-06-16");
		newAssignment("Web Development", "Immanuel Giovano", ["Jack", "Jillian P. Tanuwijaya", "Jocheli Kensi Budianti"], "Assignment 3 Web Development", "2026-06-23");
		newAssignment("Web Development", "Immanuel Giovano", ["Jack", "Jillian P. Tanuwijaya", "Jocheli Kensi Budianti"], "Assignment 4 Web Development", "2027-06-30");


		/* Submissions for assignments in Hari's courses */
		newSubmissions("Digital Drawing", "Hari", "Jack", "Assignment 1 Digital Drawing", ["Submission Assignment 1 Digital Drawing - Jack", "Revision Assignment 1 Digital Drawing - Jack"]);
		newSubmissions("Digital Drawing", "Hari", "Jillian P. Tanuwijaya", "Assignment 1 Digital Drawing", ["Submission Assignment 1 Digital Drawing - Jillian P. Tanuwijaya"]);
		newSubmissions("Digital Drawing", "Hari", "Jocheli Kensi Budianti", "Assignment 1 Digital Drawing", ["Submission 1 Assignment 1 Digital Drawing - Jocheli Kensi Budianti", "Submission 2 Assignment 1 Digital Drawing - Jocheli Kensi Budianti", "Submission 3 Assignment 1 Digital Drawing - Jocheli Kensi Budianti"]);

		newSubmissions("Digital Drawing", "Hari", "Jack", "Assignment 2 Digital Drawing", ["Submission Assignment 2 Digital Drawing - Jack"]);
		newSubmissions("Digital Drawing", "Hari", "Jillian P. Tanuwijaya", "Assignment 2 Digital Drawing", ["Submission Assignment 2 Digital Drawing - Jillian P. Tanuwijaya", "Revision Assignment 2 Digital Drawing - Jillian P. Tanuwijaya"]);

		/* Submissions for assignments in Iswan Sudaryo's courses */
		newSubmissions("Concept Art", "Iswan Sudaryo", "Ethan Alexander Irawan", "Assignment 1 Concept Art", ["Submission Assignment 1 Concept Art - Ethan Alexander Irawan", "Revision Assignment 1 Concept Art - Ethan Alexander Irawan"]);
		newSubmissions("Concept Art", "Iswan Sudaryo", "Jezriel Connery", "Assignment 1 Concept Art", ["Submission Assignment 1 Concept Art - Jezriel Connery", "Revision Assignment 1 Concept Art - Jezriel Connery"]);
		newSubmissions("Concept Art", "Iswan Sudaryo", "Martha Theresia Ramlie", "Assignment 1 Concept Art", ["Submission Assignment 1 Concept Art - Martha Theresia Ramlie"]);
		newSubmissions("Concept Art", "Iswan Sudaryo", "Janicelyn Daviena Godarma", "Assignment 1 Concept Art", ["Submission Assignment 1 Concept Art - Janicelyn Daviena Godarma", "Revision Assignment 1 Concept Art - Janicelyn Daviena Godarma"]);
		newSubmissions("Concept Art", "Iswan Sudaryo", "Grace Devana Kusnandar", "Assignment 1 Concept Art", ["Submission Assignment 1 Concept Art - Grace Devana Kusnandar"]);

		newSubmissions("Concept Art", "Iswan Sudaryo", "Jezriel Connery", "Assignment 2 Concept Art", ["Submission Assignment 2 Concept Art - Jezriel Connery"]);
		newSubmissions("Concept Art", "Iswan Sudaryo", "Janicelyn Daviena Godarma", "Assignment 2 Concept Art", ["Submission Assignment 2 Concept Art - Janicelyn Daviena Godarma", "Revision Assignment 2 Concept Art - Janicelyn Daviena Godarma"]);
		newSubmissions("Concept Art", "Iswan Sudaryo", "Grace Devana Kusnandar", "Assignment 2 Concept Art", ["Submission Assignment 2 Concept Art - Grace Devana Kusnandar", "Revision Assignment 2 Concept Art - Grace Devana Kusnandar"]);

		/* Submissions for assignments in Vincent's courses */
		newSubmissions("3D Modelling", "Vincent", "Philia Valeraine Alverna", "Assignment 1 3D Modelling", ["Submission Assignment 1 3D Modelling - Philia Valeraine Alverna", "Revision Assignment 1 3D Modelling - Philia Valeraine Alverna"]);
		newSubmissions("3D Modelling", "Vincent", "Kensi Sinclair", "Assignment 1 3D Modelling", ["Submission Assignment 1 3D Modelling - Kensi Sinclair", "Revision Assignment 1 3D Modelling - Kensi Sinclair"]);
		newSubmissions("3D Modelling", "Vincent", "Giselle Saputra", "Assignment 1 3D Modelling", ["Submission 1 Assignment 1 3D Modelling - Giselle Saputra", "Submission 2 Assignment 1 3D Modelling - Giselle Saputra", "Revision Assignment 1 3D Modelling - Giselle Saputra"]);

		/* Submissions for assignments in Gaby's courses */
		newSubmissions("Digital Drawing", "Gaby", "Melly Tanto", "Assignment 1 Digital Drawing", ["Submission Assignment 1 Digital Drawing - Melly Tanto"]);
		newSubmissions("Digital Drawing", "Gaby", "Zhafira Jasmine", "Assignment 1 Digital Drawing", ["Submission Assignment 1 Digital Drawing - Zhafira Jasmine", "Revision Assignment 1 Digital Drawing - Zhafira Jasmine"]);
		newSubmissions("Digital Drawing", "Gaby", "Freya Pramudia", "Assignment 1 Digital Drawing", ["Submission Assignment 1 Digital Drawing - Freya Pramudia"]);

		newSubmissions("Digital Drawing", "Gaby", "Melly Tanto", "Assignment 2 Digital Drawing", ["Submission Assignment 2 Digital Drawing - Melly Tanto", "Revision Assignment 2 Digital Drawing - Melly Tanto"]);
		newSubmissions("Digital Drawing", "Gaby", "Zhafira Jasmine", "Assignment 2 Digital Drawing", ["Submission Assignment 2 Digital Drawing - Zhafira Jasmine"]);
		newSubmissions("Digital Drawing", "Gaby", "Vanya Farelia", "Assignment 2 Digital Drawing", ["Submission Assignment 2 Digital Drawing - Vanya Farelia", "Revision Assignment 2 Digital Drawing - Vanya Farelia"]);
		newSubmissions("Digital Drawing", "Gaby", "Freya Pramudia", "Assignment 2 Digital Drawing", ["Submission Assignment 2 Digital Drawing - Freya Pramudia"]);

		newSubmissions("Digital Drawing", "Gaby", "Vanya Farelia", "Assignment 3 Digital Drawing", ["Submission Assignment 3 Digital Drawing - Vanya Farelia", "Revision Assignment 3 Digital Drawing - Vanya Farelia"]);
		newSubmissions("Digital Drawing", "Gaby", "Freya Pramudia", "Assignment 3 Digital Drawing", ["Submission Assignment 3 Digital Drawing - Freya Pramudia"]);
    }
}
