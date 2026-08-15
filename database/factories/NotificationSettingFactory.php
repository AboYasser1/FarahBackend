<?php

namespace Database\Factories;

use App\Models\NotificationSetting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NotificationSetting>
 */
class NotificationSettingFactory extends Factory
{
    protected $model = NotificationSetting::class;

    public function definition(): array
    {
        $user = User::query()->inRandomOrder()->first() ?? User::factory()->create();

        return [
            'user_id' => $user->id,
            'new_orders' => $this->faker->boolean(),
            'offers' => $this->faker->boolean(),
            'promotions' => $this->faker->boolean(),
            'reminders' => $this->faker->boolean(),
        ];
    }
}
