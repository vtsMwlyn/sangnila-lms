<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

require_once __DIR__ . "/Helpers.php";

class CourseAssignmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*Hari's students*/
		assignStudent("Jack", "Hari", "Digital Drawing", 8);
		assignStudent("Jillian P. Tanuwijaya", "Hari", "Digital Drawing", 8);
		assignStudent("Jocheli Kensi Budianti", "Hari", "Digital Drawing", 8);

		assignStudent("Jack", "Immanuel Giovano (Teacher)", "Web Development", 16);
		assignStudent("Jillian P. Tanuwijaya", "Immanuel Giovano (Teacher)", "Web Development", 16);
		assignStudent("Jocheli Kensi Budianti", "Immanuel Giovano (Teacher)", "Web Development", 16);

		assignStudent("Batara Feodore Setiawan","Hari", "Roblox", 8);
		assignStudent("Bellrich Kevin Tjahyadi","Hari", "Roblox", 8);
		assignStudent("Benedict Jacob","Hari", "Roblox", 8);

		/*Gaby's students*/
		assignStudent("Melly Tanto", "Gaby", "Digital Drawing", 16);
		assignStudent("Zhafira Jasmine", "Gaby", "Digital Drawing", 16);
		assignStudent("Vanya Farelia", "Gaby", "Digital Drawing", 16);
		assignStudent("Freya Pramudia", "Gaby", "Digital Drawing", 16);
		assignStudent("Kenzie Gautama Dirgantara", "Gaby", "Digital Drawing", 16);

		/*Iswan's students*/
		assignStudent("Louisha Annabelle", "Iswan Sudaryo (Teacher)", "3D Modelling", 8);
		assignStudent("Gayle Farrel Patria", "Iswan Sudaryo (Teacher)", "3D Modelling", 8);
		assignStudent("Angela Nathania", "Iswan Sudaryo (Teacher)", "3D Modelling", 8);
		assignStudent("Balya Malkan Mahyuzar", "Iswan Sudaryo (Teacher)", "3D Modelling", 8);
		assignStudent("Alvin Edward", "Iswan Sudaryo (Teacher)", "3D Modelling", 8);

		assignStudent("Ethan Alexander Irawan", "Iswan Sudaryo (Teacher)", "Concept Art", 8);
		assignStudent("Jezriel Connery", "Iswan Sudaryo (Teacher)", "Concept Art", 8);
		assignStudent("Martha Theresia Ramlie", "Iswan Sudaryo (Teacher)", "Concept Art", 8);
		assignStudent("Janicelyn Daviena Godarma", "Iswan Sudaryo (Teacher)", "Concept Art", 8);
		assignStudent("Grace Devana Kusnandar", "Iswan Sudaryo (Teacher)", "Concept Art", 8);

		/*Vincent's students*/
		assignStudent("Philia Valeraine Alverna", "Vincent", "3D Modelling", 24);
		assignStudent("Kensi Sinclair", "Vincent", "3D Modelling", 24);
		assignStudent("Giselle Saputra", "Vincent", "3D Modelling", 24);

		assignStudent("Chelsea", "Vincent", "2D Animation", 8);
		assignStudent("Ken", "Vincent", "2D Animation", 8);

		// Assign teachers to courses
		assignTeacher("Hari", ["Digital Drawing", "Roblox"]);
		assignTeacher("Gaby", ["Digital Drawing"]);
		assignTeacher("Iswan Sudaryo (Teacher)", ["3D Modelling", "Concept Art"]);
		assignTeacher("Vincent", ["3D Modelling", "2D Animation"]);
		assignTeacher("Immanuel Giovano (Teacher)", ["Web Development"]);
    }
}
