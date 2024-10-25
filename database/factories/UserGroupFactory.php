<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class UserGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $users;
        static $groups;
        $users = $users ?? User::pluck('id')->toArray();
        $groups = $groups ?? Group::pluck('id')->toArray();
        static $staticPairs = [];
        static $index = -1;

        if ($index === -1) {
            for ($i = 0; $i < count($users); $i++) {
                for ($j = 0; $j < count($groups); $j++) {
                    $staticPairs[] = [
                        'user_id' => $users[$i],
                        'group_id' => $groups[$j],
                    ];
                }
            }

            shuffle($staticPairs);
        }
        $index++;

        return [
            'user_id' => $staticPairs[$index]['user_id'],
            'group_id' => $staticPairs[$index]['group_id'],
        ];
    }
}
