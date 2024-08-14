<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
		$this->call([
			RolesAndUsersSeeder::class,
			CoursesSeeder::class,
			CurriculumsSeeder::class,
			TopicsAndMaterialsSeeder::class,
			CourseAssignmentsSeeder::class,
			AttendancesSeeder::class,
			AssignmentsAndSubmissionsSeeders::class
		]);
	}
}
