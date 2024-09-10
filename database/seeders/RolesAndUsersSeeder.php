<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

require_once __DIR__ . "/Helpers.php";

class RolesAndUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		/* Roles */
		Role::create(["role_name" => "Admin"]);
		Role::create(["role_name" => "Teacher"]);
		Role::create(["role_name" => "Student"]);
		// Role::create(["role_name" => "Parent"]); //postponed

        /* Admin and Teachers */
		newUser("vannestheo.sangnila@gmail.com", "Vannes Theo Sudarsono", 1, 1);
		newUser("victor.sangnila@gmail.com", "Victor", 1, 1);
		newUser("feby.sangnila@gmail.com", "Feby", 1, 2);
		newUser("tiwi.sangnila@gmail.com", "Pratiwi", 1, 2);
		newUser("jovia.sangnila@gmail.com", "Jovia", 1, 2);

		newUser("hari.sangnila@gmail.com", "Hari", 2, 1);
		newUser("lgaby.sangnila@gmail.com", "Gaby", 2, 2);
		newUser("iswansudaryo.sangnila@gmail.com", "Iswan Sudaryo", 2, 1);
		newUser("vincent.sangnila@gmail.com", "Vincent", 2, 1);
		newUser("immanuelgiovano.sangnila@gmail.com", "Immanuel Giovano", 2, 1);

		/* Hari's students: Digital Drawing, Roblox */
		newUser("jack.sangnila@gmail.com", "Jack", 3, 1);
		newUser("jillian.sangnila@gmail.com", "Jillian P. Tanuwijaya", 3, 2);
		newUser("jocheli.sangnila@gmail.com", "Jocheli Kensi Budianti", 3, 2);

		newUser("batara.sangnila@gmail.com", "Batara Feodore Setiawan", 3, 1);
		newUser("bellrich.sangnila@gmail.com", "Bellrich Kevin Tjahyadi", 3, 1);
		newUser("benedict.sangnila@gmail.com", "Benedict Jacob", 3, 2);

		/* Gaby's students: Digital Drawing */
		newUser("melly.sangnila@gmail.com", "Melly Tanto", 3, 2);
		newUser("zhafira.sangnila@gmail.com", "Zhafira Jasmine", 3, 2);
		newUser("vanya.sangnila@gmail.com", "Vanya Farelia", 3, 2);
		newUser("freya.sangnila@gmail.com", "Freya Pramudia", 3, 2);
		newUser("kenzie.sangnila@gmail.com", "Kenzie Gautama Dirgantara", 3, 1);

		/* Iswan's students: 3D Modelling, Concept Art */
		newUser("louisha.sangnila@gmail.com", "Louisha Annabelle", 3, 2);
		newUser("gayle.sangnila@gmail.com", "Gayle Farrel Patria", 3, 1);
		newUser("angela.sangnila@gmail.com", "Angela Nathania", 3, 2);
		newUser("balya.sangnila@gmail.com", "Balya Malkan Mahyuzar", 3, 1);
		newUser("alvin.sangnila@gmail.com", "Alvin Edward", 3, 1);

		newUser("ethan.sangnila@gmail.com", "Ethan Alexander Irawan", 3, 1);
		newUser("jezriel.sangnila@gmail.com", "Jezriel Connery", 3, 1);
		newUser("martha.sangnila@gmail.com", "Martha Theresia Ramlie", 3, 2);
		newUser("janicelyn.sangnila@gmail.com", "Janicelyn Daviena Godarma", 3, 2);
		newUser("grace.sangnila@gmail.com", "Grace Devana Kusnandar", 3, 2);

		/* Vincent's students: 3D Modelling, 2D Modelling */
		newUser("philia.sangnila@gmail.com", "Philia Valeraine Alverna", 3, 2);
		newUser("kensi.sangnila@gmail.com", "Kensi Sinclair", 3, 1);
		newUser("giselle.sangnila@gmail.com", "Giselle Saputra", 3, 2);

		newUser("chelsea.sangnila@gmail.com", "Chelsea", 3, 2);
		newUser("ken.sangnila@gmail.com", "Ken", 3, 1);
    }
}
