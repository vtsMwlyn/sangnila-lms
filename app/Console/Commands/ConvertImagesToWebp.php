<?php

namespace App\Console\Commands;

use App\Models\Portfolio;
use App\Models\Reimburse;
use App\Models\UserDetail;
use App\Models\Announcement;
use App\Models\Message;
use App\Models\SelfAttendance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ConvertImagesToWebp extends Command
{
    protected $signature = 'images:convert-webp';
    protected $description = 'Convert all images in storage/app/public to webp and delete original files';

    private $extensions = ['jpg', 'jpeg', 'png'];
    private $converted = 0;

    private function convertAndDelete($curr_path){
        // Use the absolute path directly without prepending storage_path
        $basePath = storage_path('app/public/');
    
        // $curr_path is already relative, so no need to prepend anything
        $curr_path = $basePath . $curr_path;
    
        $manager = new ImageManager(new Driver());
    
        $extension = strtolower(pathinfo($curr_path, PATHINFO_EXTENSION));
        if (in_array($extension, $this->extensions) && file_exists($curr_path)) {
            $new_path = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $curr_path);
    
            try {
                $image = $manager->read($curr_path);
                $image->toWebp(80)->save($new_path);
    
                File::delete($curr_path);
                $this->info("Converted and deleted: " . $curr_path);
                $this->converted++;
    
                return $new_path;
            }
            catch (\Exception $e) {
                $this->error("Failed to convert: " . $curr_path . " - " . $e->getMessage());
            }
        } else {
            $this->error("File not found or invalid extension: " . $curr_path);
        }
    
        return null;
    }
    
    

    public function handle()
    {
        foreach (Announcement::all() as $announcement) {
            $convertedPath = $this->convertAndDelete($announcement->image_path);

            if($convertedPath){
                $announcement->image_path = $convertedPath;
                $announcement->save();
            }
        }

        foreach (SelfAttendance::all() as $self_attendance) {
            $convertedPath = $this->convertAndDelete($self_attendance->attendance_evidence);

            if($convertedPath){
                $self_attendance->attendance_evidence = $convertedPath;
                $self_attendance->save();
            }
        }

        foreach (Portfolio::all() as $portfolio){
            $convertedPath = $this->convertAndDelete($portfolio->path);

            if($convertedPath){
                $portfolio->path = $convertedPath;
                $portfolio->save();
            }
        }

        foreach (UserDetail::all() as $user_detail){
            $convertedPath = $this->convertAndDelete($user_detail->profpic);

            if($convertedPath){
                $user_detail->profpic = $convertedPath;
                $user_detail->save();
            }
        }

        foreach (Reimburse::all() as $reimburse){
            $convertedPath = $this->convertAndDelete($reimburse->evidence_path);

            if($convertedPath){
                $reimburse->evidence_path = $convertedPath;
                $reimburse->save();
            }
        }

        foreach (Message::all() as $message){
            $convertedPath = $this->convertAndDelete($message->attachment_path);

            if($convertedPath){
                $message->attachment_path = $convertedPath;
                $message->save();
            }
        }

        $this->info("✅ Done! Total converted: $this->converted");
        return Command::SUCCESS;
    }
}
