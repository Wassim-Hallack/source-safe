<?php

namespace Database\Factories;

use App\Models\AddFileRequest;
use App\Models\AddFileRequestToUser;
use App\Models\File;
use App\Models\Group;
use App\Models\UserGroup;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AddFileRequest>
 */
class AddFileRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $group_id = $this->faker->numberBetween(1, 10);
        $group = Group::find($group_id);

        $fileContent = $this->faker->paragraph;
        $fileName = $this->faker->word . '.txt';
        while (true) {
            $is_exists = File::where('name', $fileName)
                ->where('group_id', $group_id)
                ->exists();

            if (!$is_exists) {
                break;
            } else {
                $fileName = $this->faker->word . '.txt';
            }
        }
        $filePath = "Add File Requests/" . $group['name'] . "/" . $fileName;
        Storage::put($filePath, $fileContent);

        $isFree = $this->faker->boolean;

        return [
            'group_id' => $group_id,
            'name' => $fileName,
            'isFree' => $isFree
        ];
    }
}
