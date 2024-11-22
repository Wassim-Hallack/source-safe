<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\AddFileRequest;
use App\Models\AddFileRequestToUser;
use App\Models\File;
use App\Models\FileOperation;
use App\Models\Group;
use App\Models\GroupFile;
use App\Models\GroupInvitation;
use App\Models\UserFile;
use App\Models\UserGroup;
use Database\Factories\GroupInvitationFactory;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'email' => 'wassim@gmail.com',
            'password' => bcrypt('123456'),
            'name' => 'wassim',
            'image' => 'https://th.bing.com/th/id/OIP.v36XBUcQei_95GjMzpAGRgHaLH?w=181&h=272&c=7&r=0&o=5&dpr=1.4&pid=1.7',
        ]);
        User::create([
            'email' => 'wassim2@gmail.com',
            'password' => bcrypt('123456'),
            'name' => 'wassim2',
            'image' => 'https://thumbs.dreamstime.com/z/cool-guy-8809192.jpg',
        ]);
        User::create([
            'email' => 'wassim3@gmail.com',
            'password' => bcrypt('123456'),
            'name' => 'wassim3',
            'image' => 'https://th.bing.com/th/id/OIP.tHP9-Z5XX7fvzAjPnLgeXAHaLH?rs=1&pid=ImgDetMain',
        ]);
        User::create([
            'email' => 'wassim4@gmail.com',
            'password' => bcrypt('123456'),
            'name' => 'wassim3',
            'image' => 'https://th.bing.com/th/id/OIP.tHP9-Z5XX7fvzAjPnLgeXAHaLH?rs=1&pid=ImgDetMain',
        ]);

        Group::factory(10)->create();
        UserGroup::factory(15)->create();
        GroupInvitation::factory(7)->create();
        File::factory(100)->create();
        UserFile::factory(50)->create();
        $add_file_requests = AddFileRequest::factory(20)->create();

        foreach ($add_file_requests as $add_file_request) {
            if (!$add_file_request['isFree']) {
                while (true) {
                    $user_id = 4;
                    $exists = UserGroup::where('user_id', $user_id)->where('group_id', $add_file_request['group_id'])->exists();

                    if ($exists) {
                        AddFileRequestToUser::create([
                            'add_file_request_id' => $add_file_request['id'],
                            'user_id' => $user_id
                        ]);

                        break;
                    }
                }
            }
        }

        FileOperation::factory(50)->create();
    }
}
