<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Announcement;
use Illuminate\Database\Seeder;
use App\Models\AnnouncementUser;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
		// Generate other data
		$this->call([
			RolesAndUsersSeeder::class,
			CoursesSeeder::class,
			CurriculumsSeeder::class,
			TopicsAndMaterialsSeeder::class,
			CourseAssignmentsSeeder::class,
			AttendancesSeeder::class,
			AssignmentsAndSubmissionsSeeders::class
		]);

		// Clear all existing images
		$directory = storage_path('app/public/announcement-images');

		if (File::exists($directory)) {
			File::delete(File::files($directory));
		}

		// Make announcement
		$publicImagePath = public_path('img/sangnila_lms.png');
		$storageImagePath = 'announcement-images/sangnila_lms.png';

		Storage::put($storageImagePath, file_get_contents($publicImagePath));

		$newAnnouncement = Announcement::create([
			"title" => "Sangnila LMS version " . trans("strings.version"),
			"content" => "<div><strong>Welcome to </strong><strong><em>Sangnila Learning Management System</em></strong><strong>!!</strong><br><br>This web application is made to control learning activity in Sangnila <em>Arts Academy</em>. Here teachers can post topics and materials also assignments that can be access by students and upload attendance report. Also the learning activity data is able to be monitored by our admin and can be used for other needs.<br><br><strong>Change logs:</strong><br><br><ul><li>Added notification inboxes for teachers after students submit their assignment</li><li>Added notification inboxes for students after the teacher posted assignments, unlocked materials, and commented on submissions</li><li>Added undo button for undo check all students when teachers uploading new assignments</li><li>Bug fixes in edit attendance page</li></ul><br>Hope you enjoy the application, and if you encounter a problem please kindly contact us. Our team will help and assist you. Happy learning~<br><br><br>Best regard,<br>Sangnila <em>Arts Academy</em></div>",
			"image_path" => $storageImagePath,
			"announce_from" => date(now()),
			"announce_until" => "2025-01-01 23:59:59",
			"sent_to" => json_encode(["on", "on", "on"])
		]);
	}
}
