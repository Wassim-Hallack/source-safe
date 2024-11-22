<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\UserFile;
use App\Models\UserGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FileOperation>
 */
class FileOperationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        while(true) {
            $user_id = $this->faker->numberBetween(1, 3);
            $file_id = $this->faker->numberBetween(1, 100);
            $group = File::find($file_id)->group;

            $is_exists = UserGroup::where('user_id', $user_id)
                ->where('group_id', $group['id'])
                ->exists();

            if($is_exists) {
                $tmp = $this->faker->boolean;
                if($tmp) {
                    $operation = "check-in";
                }
                else {
                    $operation = "check-out";
                }

                return [
                    'user_id' => $user_id,
                    'file_id' => $file_id,
                    'operation' => $operation
                ];
            }
        }
    }
}
