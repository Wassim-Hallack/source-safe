<?php

namespace App\Console\Commands;

use App\Repositories\FileRepository;
use App\Repositories\UserFileRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ReleaseReservedFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'UserFile:release-reserved-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Release files reserved by user after 48 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $threshold = $now->subHours(48);

        $user_files = UserFileRepository::findAllByConditions(['created_at' => ['<=', $threshold]]);
        foreach ($user_files as $user_file) {
            $file = $user_file->file;

            UserFileRepository::delete($user_file);
            FileRepository::update($file, ['isFree' => true]);
        }
    }
}
