<?php

namespace App\Imports;

use Exception;
use App\Models\Topic;
use App\Models\Course;
use App\Models\Activity;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TopicsAndActivitiesImport implements ToModel, WithHeadingRow
{
	private $course_id;

	public function __construct($course_id){
		$this->course_id = $course_id;
	}

    public function model(array $row)
    {
        try {
            // Start transaction
            DB::beginTransaction();

			$course = Course::findOrFail($this->course_id);

			$topic = Topic::updateOrCreate([
				"user_id" => Auth::user()->id,
				"course_id" => $course->id,
				"title" => $row["topic"]
			]);

			Activity::create([
				"topic_id" => $topic->id,
				"session" => $row["session"],
				"title" => $row["activity"],
				"desc" => $row["description"],
				"link" => $row["link"]
			]);

            // Commit the transaction
            DB::commit();
        }

		catch (Exception $e) {
			dd($e->getMessage());

            // Rollback the transaction if an exception occurs
            DB::rollBack();

			// Optionally rethrow the exception to let higher-level handlers deal with it
			throw $e;
        }
    }
}
