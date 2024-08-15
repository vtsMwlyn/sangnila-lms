<?php

namespace App\Imports;

use Exception;
use App\Models\Course;
use App\Models\CurriculumMaterial;
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

			$existing_ctopic = CurriculumTopic::where("title", $row["topic"])->first();

			if($existing_ctopic){
				$ctopic = $existing_ctopic;
			}
			else {
				$ctopic = CurriculumTopic::create([
					"course_id" => $course->id,
					"title" => $row["topic"]
				]);
			}

			CurriculumMaterial::create([
				"curriculum_topic_id" => $ctopic->id,
				"title" => $row["material"],
				"desc" => $row["description"],
				"link" => $row["link"]
			]);

            // Commit the transaction
            DB::commit();
        }

		catch (Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollBack();

			// Optionally rethrow the exception to let higher-level handlers deal with it
			throw $e;
        }
    }
}
