<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\UserFile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class UserFileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = UserFile::class;

    public function definition(): array
    {
        $freeFile = File::where('isFree', true)->inRandomOrder()->first();

        if (!$freeFile) {
            return [];
        }

        $freeFile['isFree'] = true;
        $freeFile->save();


        return [
            'user_id' => $this->faker->numberBetween(1, 3),
            'file_id' => $freeFile->id,
        ];
    }
}
