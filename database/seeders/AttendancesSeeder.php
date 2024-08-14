<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

require_once __DIR__ . "/Helpers.php";

class AttendancesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* For Iswan Sudaryo (Teacher)'s Students */
		newAttendance("3D Modelling", "Iswan Sudaryo (Teacher)", ["Louisha Annabelle", "Gayle Farrel Patria", "Angela Nathania", "Balya Malkan Mahyuzar", "Alvin Edward"], "2024-06-20");
		newAttendance("3D Modelling", "Iswan Sudaryo (Teacher)", ["Gayle Farrel Patria", "Angela Nathania", "Balya Malkan Mahyuzar", "Alvin Edward"], "2024-06-27");

		newAttendance("Concept Art", "Iswan Sudaryo (Teacher)", ["Martha Theresia Ramlie", "Janicelyn Daviena Godarma", "Grace Devana Kusnandar"], "2024-06-21");

		/* For Vincent's Students */
		newAttendance("3D Modelling", "Vincent", ["Philia Valeraine Alverna", "Kensi Sinclair", "Giselle Saputra"], "2024-06-18");
		newAttendance("3D Modelling", "Vincent", ["Philia Valeraine Alverna", "Kensi Sinclair", "Giselle Saputra"], "2025-06-26");

		newAttendance("2D Animation", "Vincent", ["Ken", "Chelsea"], "2024-06-10");
		newAttendance("2D Animation", "Vincent", ["Chelsea"], "2024-06-17");

		importAndAssign("Kenzo", "kenzo.sangnila@gmail.com", 2, "2D Animation", "Vincent", 8, 7);
		newAttendance("2D Animation", "Vincent", ["Ken", "Chelsea", "Kenzo"], "2024-06-17");
		newAttendance("2D Animation", "Vincent", ["Ken", "Kenzo"], "2024-06-17");

		importAndAssign("Melissa", "melissa.sangnila@gmail.com", 2, "2D Animation", "Vincent", 16, 15);

		/* For Hari's Students */
		newAttendance("Digital Drawing", "Hari", ["Jack", "Jillian P. Tanuwijaya", "Jocheli Kensi Budianti"], "2024-06-15");
		newAttendance("Digital Drawing", "Hari", ["Jack", "Jillian P. Tanuwijaya", "Jocheli Kensi Budianti"], "2024-06-22");
		newAttendance("Digital Drawing", "Hari", ["Jack", "Jocheli Kensi Budianti"], "2024-06-29");
		newAttendance("Digital Drawing", "Hari", ["Jack", "Jillian P. Tanuwijaya", "Jocheli Kensi Budianti"], "2024-07-06");
		newAttendance("Digital Drawing", "Hari", ["Jack", "Jillian P. Tanuwijaya", "Jocheli Kensi Budianti"], "2024-07-13");
		newAttendance("Digital Drawing", "Hari", ["Jack", "Jillian P. Tanuwijaya", "Jocheli Kensi Budianti"], "2024-07-20");
		newAttendance("Digital Drawing", "Hari", ["Jocheli Kensi Budianti"], "2024-07-27");
		newAttendance("Digital Drawing", "Hari", ["Jillian P. Tanuwijaya"], "2024-08-03");

		/* For Gaby's Students */
		newAttendance("Digital Drawing", "Gaby", ["Melly Tanto", "Zhafira Jasmine", "Vanya Farelia", "Freya Pramudia", "Kenzie Gautama Dirgantara"], "2024-06-01");

		/* For Immanuel Giovano (Teacher)'s Students */
		newAttendance("Web Development", "Immanuel Giovano (Teacher)", ["Jack", "Jillian P. Tanuwijaya"], "2024-06-09");
		newAttendance("Web Development", "Immanuel Giovano (Teacher)", ["Jack", "Jocheli Kensi Budianti"], "2025-06-16");
    }
}
