<?php

namespace Database\Factories;

use App\Models\File;
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
        $fileName = $this->faker->word . '.txt';
        $fileContent = $this->faker->paragraph;
        Storage::disk('local')->put($fileName, $fileContent);

        return [
            'name' => $fileName,
            'group_id' => $this->faker->numberBetween(1, 10),
        ];
    }
}
