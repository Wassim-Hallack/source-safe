<?php

namespace Database\Factories;

use App\Models\UserGroup;
use Illuminate\Database\Eloquent\Factories\Factory;
use function PHPUnit\Framework\isEmpty;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GroupInvitation>
 */
class GroupInvitationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        while (true) {
            $user_id = $this->faker->numberBetween(1, 3);
            $group_id = $this->faker->numberBetween(1, 10);
            $tmp = UserGroup::where('user_id', $user_id)->where('group_id', $group_id)->exists();

            if (!$tmp) {
                return [
                    'user_id' => $user_id,
                    'group_id' => $group_id,
                ];
            }
        }
    }
}
