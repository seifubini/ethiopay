<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;

class CreateDirectoryCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'directory:create-upload-directories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Directory';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $storagePath = storage_path() . "/app/public/";
        $directoriesPath = array(
            "uploadsDir" => $storagePath . "uploads/",
            "userProfile" => $storagePath . "uploads/profile",
            //"userProfileLarge" => $storagePath . "uploads/profile/large",
            "userProfileMedium" => $storagePath . "uploads/profile/medium",
            "userProfileOriginal" => $storagePath . "uploads/profile/original",
            "userProfileSmall" => $storagePath . "uploads/profile/small"
        );
        foreach ($directoriesPath as $key => $value) {
            if (!File::exists($value)) {
                $oldmask = umask(0);
                File::makeDirectory($value, 0777, true, true);
                umask($oldmask);
            }
        }
    }

}
