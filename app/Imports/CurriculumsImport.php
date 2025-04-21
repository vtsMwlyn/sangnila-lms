<?php

namespace App\Imports;

use Exception;
use App\Models\Course;
use App\Models\CurriculumActivity;
use App\Models\CurriculumTopic;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CurriculumsImport implements ToModel, WithHeadingRow
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

			$ctopic = CurriculumTopic::updateOrCreate([
				"course_id" => $course->id,
				"title" => $row["topic"]
			]);

			CurriculumActivity::create([
				"curriculum_topic_id" => $ctopic->id,
				"session" => $row["session"],
				"title" => $row["activity"],
				"desc" => $row["description"],
				"link" => $row["link"],
			]);

            // Commit the transaction
            DB::commit();
        }

		catch (Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollBack();

			// Optionally rethrow the exception to let higher-level handlers deal with it
			return back()->with("danger", "System failed to import old student data, please report the error to our IT team. Error detail: " . $e->getMessage());
        }
    }
}
