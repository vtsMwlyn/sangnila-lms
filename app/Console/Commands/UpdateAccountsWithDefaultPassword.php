<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Message;
use App\Models\Portfolio;
use App\Models\Reimburse;
use App\Models\UserDetail;
use Illuminate\Support\Str;
use App\Models\Announcement;
use App\Models\SelfAttendance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class UpdateAccountsWithDefaultPassword extends Command
{
    protected $signature = 'account:update-default-password';
    protected $description = 'Use this command to update all accounts those are currently still using the default password';

    private $counter = 0;
    
    public function handle()
    {
        try {
            DB::beginTransaction();

            foreach(User::all() as $user){
                if (Hash::check(trans('strings.old_default_password'), $user->password)) {
                    $user->password = Hash::make(trans('strings.default_password'));
                    $user->save();

                    $this->info('Successfully updated the password for account ' . $user->full_name . ' (' . $user->role->role_name . ')');
                    $this->counter++;
                }
            }

            DB::commit();
        }
        catch(\Exception $e){
            DB::rollback();

            $this->error('Error occured.');
        }

        $this->info("✅ Done! Total updated: $this->counter");
        return Command::SUCCESS;
    }
}
