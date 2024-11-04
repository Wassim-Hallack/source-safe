<?php

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Group::class;

    public function definition(): array
    {
        $name = fake()->name();
        $is_exists = Group::where('name', $name)->exists();
        while(true) {
            if(!$is_exists) {
                break;
            }
            else {
                $name = fake()->name();
                $is_exists = Group::where('name', $name)->exists();
            }
        }

        return [
            'name' => $name,
            'user_id' => $this->faker->numberBetween(1, 3),
        ];
    }
}
