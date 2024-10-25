<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\File;
use App\Models\Group;
use App\Models\GroupFile;
use App\Models\GroupInvitation;
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

        Group::factory(10)->create();
        UserGroup::factory(15)->create();
        File::factory(100)->create();
        GroupInvitation::factory(7)->create();

//        // Create relations files with at least one group
//        $files = File::get();
//        foreach ($files as $file) {
//            GroupFile::factory()->create([
//                'file_id' => $file->id,
//                'group_id' => Group::inRandomOrder()->first()->id,
//            ]);
//        }

        // Create relations for 35 files with at least one user
//        $filesWithRelations = File::inRandomOrder()->take(35)->get();
//        foreach ($filesWithRelations as $file) {
//            UserFile::factory()->create([
//                'file_id' => $file->id,
//                'user_id' => User::inRandomOrder()->first()->id,
//            ]);
//
//            $file['isFree'] = false;
//            $file->save();
//        }

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
