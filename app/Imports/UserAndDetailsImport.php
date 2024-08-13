<?php

namespace App\Imports;

use App\Models\User;
use App\Models\UserDetail;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserAndDetailsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        try {
            // Start transaction
            DB::beginTransaction();

            // Check if the user already exists
            $existingUser = User::where("email", $row["email"])->first();

            if ($existingUser) {
                $user = $existingUser;
            } else {
                // Create a new user
                $user = User::create([
                    "full_name" => $row["name"],
                    "email" => $row["email"],
                    "role_id" => 3,
                    "status" => "enabled",
                    "password" => bcrypt(trans("strings.default_password"))
                ]);
            }

            // Check if user details already exist
            $existingUserDetail = UserDetail::where("user_id", $user->id)->first();
            if ($existingUserDetail) {
                // Update existing user details
                UserDetail::where("user_id", $user->id)->update([
                    "gender"  => $row["gender"],
                    "phone_number" => $row["phone_number"],
                    "city_of_birth" => $row["city_of_birth"],
                    "date_of_birth" => $row["date_of_birth"],
                    "name_parent" => $row["name_parent"],
                    "phone_parent" => $row["phone_parent"],
                    "student_level" => $row["student_level"],
                    "school_name" => $row["school_name"],
                ]);
            } else {
                // Create new user details
                UserDetail::create([
                    "user_id" => $user->id,
                    "gender"  => $row["gender"],
                    "phone_number" => $row["phone_number"],
                    "city_of_birth" => $row["city_of_birth"],
                    "date_of_birth" => $row["date_of_birth"],
                    "name_parent" => $row["name_parent"],
                    "phone_parent" => $row["phone_parent"],
                    "student_level" => $row["student_level"],
                    "school_name" => $row["school_name"],
                ]);
            }

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
