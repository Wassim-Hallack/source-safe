<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = File::class;

    public function definition(): array
    {
        $group_id = $this->faker->numberBetween(1, 10);
        $group = Group::find($group_id);

        $fileContent = $this->faker->paragraph;
        $fileName = $this->faker->name() . '.txt';
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

        $filePath = "Groups/" . $group['name'] . "/" . $fileName . "/1.txt";
        Storage::put($filePath, $fileContent);

        return [
            'name' => $fileName,
            'group_id' => $group_id,
        ];
    }
}
